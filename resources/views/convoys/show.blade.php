@extends('layouts.app')

@section('title', $convoy->name . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Departs {{ $convoy->departs_at->format('l j F · g:i A') }}</p>
            <h1 class="page-title">{{ $convoy->name }}</h1>
            @if ($convoy->notes)
                <p class="intro-description">{{ $convoy->notes }}</p>
            @endif

            <div class="map" data-map data-tile-url="{{ config('services.map.tile_url') }}" data-points='@json($convoy->travelRoute->waypoints)' role="img" aria-label="Convoy route map"></div>

            <div class="event-detail">
                <div>
                    <h2 class="subheading">Route stops</h2>
                    <ol class="waypoint-list">
                        @foreach ($convoy->travelRoute->waypoints as $point)
                            <li>
                                <strong>{{ $point['label'] ?: ($loop->first ? 'Start' : ($loop->last ? 'Finish' : 'Stop '.$loop->iteration)) }}</strong>
                                <span>{{ number_format($point['lat'], 4) }}, {{ number_format($point['lng'], 4) }}</span>
                            </li>
                        @endforeach
                    </ol>

                    @if ($members->isNotEmpty())
                        <h2 class="subheading">In this convoy</h2>
                        <ul class="plain-list">
                            @foreach ($members as $member)
                                <li>{{ $member->name }}@if ($member->organization_name) <span>{{ $member->organization_name }}</span>@endif</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <aside class="event-detail-side" aria-label="Convoy details">
                    <dl class="detail-list">
                        <div><dt>Led by</dt><dd>{{ $convoy->host->name }}</dd></div>
                        <div><dt>Route</dt><dd><a href="{{ route('routes.show', $convoy->travelRoute) }}">{{ $convoy->travelRoute->name }}</a> · {{ number_format($convoy->travelRoute->distance_km, 1) }} km</dd></div>
                        @if ($convoy->event)
                            <div><dt>Heading to</dt><dd><a href="{{ route('events.show', $convoy->event) }}">{{ $convoy->event->title }}</a></dd></div>
                        @endif
                        <div><dt>Seats</dt><dd>{{ $convoy->members_count }} of {{ $convoy->capacity }} taken</dd></div>
                    </dl>

                    @error('membership')
                        <p class="flash flash-error" role="alert">{{ $message }}</p>
                    @enderror

                    @if ($convoy->departs_at->isPast())
                        <p class="form-note">This convoy has departed.</p>
                    @elseif (! auth()->check())
                        <a class="button button-block" href="{{ route('login') }}">Log in to join</a>
                    @elseif ($isMember)
                        @if (auth()->id() === $convoy->host_id)
                            <p class="form-note">You are leading this convoy.</p>
                        @else
                            <form method="POST" action="{{ route('convoys.leave', $convoy) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button-block button-quiet" type="submit">Leave convoy</button>
                            </form>
                        @endif
                    @elseif ($convoy->isFull())
                        <p class="form-note">This convoy is full.</p>
                    @else
                        <form method="POST" action="{{ route('convoys.join', $convoy) }}">
                            @csrf
                            <button class="button button-block" type="submit">Join convoy</button>
                        </form>
                    @endif
                </aside>
            </div>
        </article>
    </div>
@endsection
