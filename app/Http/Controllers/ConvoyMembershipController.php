<?php

namespace App\Http\Controllers;

use App\Models\Convoy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvoyMembershipController extends Controller
{
    public function store(Request $request, Convoy $convoy): RedirectResponse
    {
        if ($convoy->departs_at->isPast()) {
            return back()->withErrors(['membership' => 'This convoy has already departed.']);
        }

        $joined = DB::transaction(function () use ($request, $convoy): bool {
            $convoy->newQuery()->whereKey($convoy->getKey())->lockForUpdate()->first();

            if ($convoy->members()->where('users.id', $request->user()->id)->exists()) {
                return true;
            }

            if ($convoy->isFull()) {
                return false;
            }

            $convoy->members()->attach($request->user()->id);

            return true;
        });

        if (! $joined) {
            return back()->withErrors(['membership' => 'This convoy is full.']);
        }

        return back()->with('status', 'You are in the convoy.');
    }

    public function destroy(Request $request, Convoy $convoy): RedirectResponse
    {
        if ($convoy->host_id === $request->user()->id) {
            return back()->withErrors(['membership' => 'The host cannot leave their own convoy.']);
        }

        $convoy->members()->detach($request->user()->id);

        return back()->with('status', 'You left the convoy.');
    }
}
