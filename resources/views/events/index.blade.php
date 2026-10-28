@extends('layouts.app')

@section('title', 'Gatherings | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="events-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Start with a handshake</p>
                    <h1 class="page-title" id="events-title">Gatherings</h1>
                </div>
                @auth
                    <a class="button" href="{{ route('events.create') }}">Host a gathering</a>
                @endauth
            </div>

            <form class="filters" method="GET" action="{{ route('events.index') }}">
                <input type="search" name="q" value="{{ $search }}" placeholder="Search gatherings" aria-label="Search gatherings">
                <select name="city" aria-label="City">
                    <option value="">All cities</option>
                    @foreach ($cities as $cityOption)
                        <option value="{{ $cityOption }}" @selected($city === $cityOption)>{{ $cityOption }}</option>
                    @endforeach
                </select>
                <select name="when" aria-label="When">
                    <option value="">Upcoming</option>
                    <option value="past" @selected($showPast)>Past</option>
                </select>
                <button class="button button-small" type="submit">Filter</button>
            </form>

            @if ($events->isEmpty())
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">&#10022;</span>
                    <div>
                        <h3>No gatherings on the calendar yet.</h3>
                        <p>Check back soon, or host the first one yourself.</p>
                    </div>
                    <span class="empty-state-note">COMING INTO FOCUS</span>
                </div>
            @else
                <div class="event-grid">
                    @foreach ($events as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>

                {{ $events->links() }}
            @endif
        </section>
    </div>
@endsection
