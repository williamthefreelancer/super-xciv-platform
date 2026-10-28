<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConvoyRequest;
use App\Models\Convoy;
use App\Models\Event;
use App\Models\TravelRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConvoyController extends Controller
{
    public function index(): View
    {
        return view('convoys.index', [
            'convoys' => Convoy::query()
                ->upcoming()
                ->with(['host', 'travelRoute'])
                ->withCount('members')
                ->paginate(9),
        ]);
    }

    public function show(Request $request, Convoy $convoy): View
    {
        $convoy->load(['host', 'travelRoute', 'event'])->loadCount('members');

        $isMember = $request->user() !== null
            && $convoy->members()->where('users.id', $request->user()->id)->exists();

        return view('convoys.show', [
            'convoy' => $convoy,
            'isMember' => $isMember,
            'members' => $isMember ? $convoy->members()->orderBy('name')->get() : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('convoys.create', [
            'routes' => TravelRoute::orderBy('name')->get(['id', 'name', 'distance_km']),
            'events' => Event::upcoming()->get(['id', 'title']),
            'selectedRoute' => $request->query('route'),
        ]);
    }

    public function store(StoreConvoyRequest $request): RedirectResponse
    {
        $convoy = $request->user()->hostedConvoys()->create($request->validated());
        $convoy->members()->attach($request->user()->id);

        return redirect()->route('convoys.show', $convoy)->with('status', 'Convoy created.');
    }
}
