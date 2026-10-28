<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $city = trim((string) $request->query('city', ''));
        $showPast = $request->query('when') === 'past';

        $events = Event::query()
            ->with(['host', 'community'])
            ->withCount('registrations')
            ->when(
                $showPast,
                fn ($query) => $query->where('starts_at', '<', now())->orderByDesc('starts_at'),
                fn ($query) => $query->upcoming(),
            )
            ->when($search !== '', fn ($query) => $query->where(
                fn ($inner) => $inner->where('title', 'like', '%'.$search.'%')->orWhere('summary', 'like', '%'.$search.'%'),
            ))
            ->when($city !== '', fn ($query) => $query->where('city', $city))
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'cities' => Event::query()->distinct()->orderBy('city')->pluck('city'),
            'search' => $search,
            'city' => $city,
            'showPast' => $showPast,
        ]);
    }

    public function show(Request $request, Event $event): View
    {
        $event->load(['host', 'community'])->loadCount('registrations');

        $user = $request->user();

        return view('events.show', [
            'event' => $event,
            'isRegistered' => $user !== null && $event->registrations()->where('user_id', $user->id)->exists(),
            'attendees' => $user?->id === $event->host_id
                ? $event->attendees()->orderBy('name')->get()
                : collect(),
            'broadcasts' => $event->broadcasts()->orderByDesc('scheduled_at')->get(),
            'convoys' => $event->convoys()->upcoming()->withCount('members')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('events.create', [
            'communities' => $request->user()->communities()->orderBy('name')->get(['communities.id', 'communities.name']),
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = $request->user()->hostedEvents()->create($request->validated());

        return redirect()->route('events.show', $event)->with('status', 'Your gathering is live.');
    }
}
