@extends('layouts.app')

@section('title', $travelRoute->name . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> {{ number_format($travelRoute->distance_km, 1) }} km · {{ count($travelRoute->waypoints) }} stops</p>
            <h1 class="page-title">{{ $travelRoute->name }}</h1>
            @if ($travelRoute->description)
                <p class="intro-description">{{ $travelRoute->description }}</p>
            @endif

            <div class="map" data-map data-tile-url="{{ config('services.map.tile_url') }}" data-points='@json($travelRoute->waypoints)' role="img" aria-label="Map of {{ $travelRoute->name }}"></div>

            <div class="event-detail">
                <div>
                    <h2 class="subheading">Stops</h2>
                    <ol class="waypoint-list">
                        @foreach ($travelRoute->waypoints as $point)
                            <li>
                                <strong>{{ $point['label'] ?: ($loop->first ? 'Start' : ($loop->last ? 'Finish' : 'Stop '.$loop->iteration)) }}</strong>
                                <span>{{ number_format($point['lat'], 4) }}, {{ number_format($point['lng'], 4) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <aside class="event-detail-side">
                    <dl class="detail-list">
                        <div><dt>Planned by</dt><dd>{{ $travelRoute->owner->name }}</dd></div>
                    </dl>
                    @auth
                        <a class="button button-block" href="{{ route('convoys.create', ['route' => $travelRoute->id]) }}">Start a convoy on this route</a>
                    @endauth
                </aside>
            </div>

            <h2 class="subheading">Upcoming convoys</h2>
            @forelse ($convoys as $convoy)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('convoys.show', $convoy) }}">{{ $convoy->name }}</a>
                    <span>{{ $convoy->departs_at->format('D j M · g:i A') }} · {{ $convoy->members_count }} of {{ $convoy->capacity }} seats</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">No convoys on this route yet.</p>
            @endforelse
        </article>
    </div>
@endsection
