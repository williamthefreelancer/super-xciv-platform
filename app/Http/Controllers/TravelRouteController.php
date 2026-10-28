<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelRouteRequest;
use App\Models\TravelRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TravelRouteController extends Controller
{
    public function index(): View
    {
        return view('routes.index', [
            'routes' => TravelRoute::query()->with('owner')->withCount('convoys')->latest()->paginate(9),
        ]);
    }

    public function show(TravelRoute $travelRoute): View
    {
        $travelRoute->load('owner');

        return view('routes.show', [
            'travelRoute' => $travelRoute,
            'convoys' => $travelRoute->convoys()->upcoming()->with('host')->withCount('members')->get(),
        ]);
    }

    public function create(): View
    {
        return view('routes.create');
    }

    public function store(StoreTravelRouteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $waypoints = array_map(fn (array $point): array => [
            'lat' => round((float) $point['lat'], 6),
            'lng' => round((float) $point['lng'], 6),
            'label' => $point['label'] ?? null,
        ], $data['waypoints']);

        $route = $request->user()->travelRoutes()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'waypoints' => $waypoints,
            'distance_km' => TravelRoute::distanceFor($waypoints),
        ]);

        return redirect()->route('routes.show', $route)->with('status', 'Route saved.');
    }
}
