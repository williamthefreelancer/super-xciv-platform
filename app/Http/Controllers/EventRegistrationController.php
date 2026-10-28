<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        if ($event->starts_at->isPast()) {
            return back()->withErrors(['registration' => 'This gathering has already taken place.']);
        }

        $joined = DB::transaction(function () use ($request, $event): bool {
            $event->newQuery()->whereKey($event->getKey())->lockForUpdate()->first();

            if ($event->registrations()->where('user_id', $request->user()->id)->exists()) {
                return true;
            }

            if ($event->isFull()) {
                return false;
            }

            $event->registrations()->create(['user_id' => $request->user()->id]);

            return true;
        });

        if (! $joined) {
            return back()->withErrors(['registration' => 'This gathering is full.']);
        }

        return back()->with('status', 'Your seat is saved.');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $event->registrations()->where('user_id', $request->user()->id)->delete();

        return back()->with('status', 'Your registration was cancelled.');
    }
}
