@extends('layouts.app')

@section('title', 'Routes | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="routes-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Plan the drive</p>
                    <h1 class="page-title" id="routes-title">Routes</h1>
                </div>
                @auth
                    <a class="button" href="{{ route('routes.create') }}">Draw a route</a>
                @endauth
            </div>

            @if ($routes->isEmpty())
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">&#10022;</span>
                    <div>
                        <h3>No routes yet.</h3>
                        <p>Draw a route on the map, then start a convoy along it.</p>
                    </div>
                    <span class="empty-state-note">OPEN ROAD</span>
                </div>
            @else
                <div class="event-grid">
                    @foreach ($routes as $route)
                        <x-info-card
                            :href="route('routes.show', $route)"
                            :title="$route->name"
                            :eyebrow="number_format($route->distance_km, 1).' km · '.count($route->waypoints).' stops'"
                            :summary="$route->description"
                            :left="'By '.$route->owner->name"
                            :right="$route->convoys_count.' '.\Illuminate\Support\Str::plural('convoy', $route->convoys_count)"
                        />
                    @endforeach
                </div>

                {{ $routes->links() }}
            @endif
        </section>
    </div>
@endsection
