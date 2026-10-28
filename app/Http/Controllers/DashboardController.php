<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'hosting' => $user->hostedEvents()->withCount('registrations')->orderBy('starts_at')->get(),
            'attending' => $user->attendingEvents()->upcoming()->get(),
            'communities' => $user->communities()->orderBy('name')->get(['communities.id', 'communities.name', 'communities.slug']),
            'broadcasts' => $user->hostedBroadcasts()->where('status', '!=', 'ended')->orderBy('scheduled_at')->get(),
            'convoys' => $user->convoys()->upcoming()->get(),
            'routes' => $user->travelRoutes()->latest()->limit(5)->get(),
        ]);
    }
}
