<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBroadcastRequest;
use App\Models\Broadcast;
use App\Services\LiveKitTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function index(): View
    {
        return view('broadcasts.index', [
            'live' => Broadcast::live()->with('host')->get(),
            'scheduled' => Broadcast::scheduled()->with('host')->limit(12)->get(),
            'ended' => Broadcast::ended()->with('host')->limit(12)->get(),
        ]);
    }

    public function show(Request $request, Broadcast $broadcast): View
    {
        $broadcast->load(['host', 'event']);

        return view('broadcasts.show', [
            'broadcast' => $broadcast,
            'playback' => $broadcast->playback(),
            'canManage' => $request->user()?->can('update', $broadcast) ?? false,
            'livekitConfigured' => filled(config('services.livekit.api_key')),
        ]);
    }

    public function create(Request $request): View
    {
        return view('broadcasts.create', [
            'events' => $request->user()->hostedEvents()->orderBy('starts_at')->get(['id', 'title']),
        ]);
    }

    public function store(StoreBroadcastRequest $request): RedirectResponse
    {
        $broadcast = $request->user()->hostedBroadcasts()->create($request->validated());

        return redirect()->route('broadcasts.show', $broadcast)->with('status', 'Broadcast scheduled.');
    }

    public function live(Request $request, Broadcast $broadcast): RedirectResponse
    {
        Gate::authorize('update', $broadcast);

        $data = $request->validate(['stream_url' => ['nullable', 'url:http,https', 'max:500']]);

        if ($broadcast->isEnded()) {
            return back()->withErrors(['broadcast' => 'This broadcast has already ended.']);
        }

        $broadcast->forceFill([
            'status' => Broadcast::STATUS_LIVE,
            'started_at' => $broadcast->started_at ?? now(),
            'stream_url' => $data['stream_url'] ?? $broadcast->stream_url,
        ])->save();

        return back()->with('status', 'You are live.');
    }

    public function end(Broadcast $broadcast): RedirectResponse
    {
        Gate::authorize('update', $broadcast);

        $broadcast->forceFill([
            'status' => Broadcast::STATUS_ENDED,
            'ended_at' => now(),
        ])->save();

        return back()->with('status', 'Broadcast ended.');
    }

    public function status(Broadcast $broadcast): JsonResponse
    {
        return response()->json(['status' => $broadcast->status]);
    }

    public function token(Request $request, Broadcast $broadcast, LiveKitTokenService $tokenService): JsonResponse
    {
        $user = $request->user();
        $isHost = $user !== null && $user->can('update', $broadcast);

        if ($user !== null) {
            $identity = "user_{$user->id}";
            $name = $user->name;
        } else {
            $sessionId = $request->hasSession() ? $request->session()->getId() : ($request->ip() ?? 'guest');
            $identity = 'guest_'.substr(md5($sessionId), 0, 8);
            $name = 'Guest '.substr(md5($sessionId), 0, 4);
        }

        $canPublish = $isHost && ! $broadcast->isEnded();

        $token = $tokenService->createToken([
            'room' => "broadcast-{$broadcast->id}",
            'identity' => $identity,
            'name' => $name,
            'canPublish' => $canPublish,
            'canSubscribe' => true,
            'canPublishData' => true,
        ]);

        return response()->json([
            'serverUrl' => $tokenService->getServerUrl(),
            'token' => $token,
            'room' => "broadcast-{$broadcast->id}",
            'canPublish' => $canPublish,
            'identity' => $identity,
            'name' => $name,
            'type' => $broadcast->type,
            'status' => $broadcast->status,
        ]);
    }
}
