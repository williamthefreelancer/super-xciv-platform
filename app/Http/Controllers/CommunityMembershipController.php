<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommunityMembershipController extends Controller
{
    public function store(Request $request, Community $community): RedirectResponse
    {
        $community->members()->syncWithoutDetaching([$request->user()->id]);

        return back()->with('status', 'You joined '.$community->name.'.');
    }

    public function destroy(Request $request, Community $community): RedirectResponse
    {
        if ($community->owner_id === $request->user()->id) {
            return back()->withErrors(['membership' => 'The owner cannot leave their own community.']);
        }

        $community->members()->detach($request->user()->id);

        return back()->with('status', 'You left '.$community->name.'.');
    }
}
