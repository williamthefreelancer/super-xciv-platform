@extends('layouts.app')

@section('title', 'Start a convoy | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page form-narrow" aria-labelledby="convoy-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Arrive together</p>
            <h1 class="page-title" id="convoy-title">Start a convoy</h1>

            @if ($routes->isEmpty())
                <p class="form-note">You need a route first. <a href="{{ route('routes.create') }}">Draw one</a>.</p>
            @else
                <form class="form" method="POST" action="{{ route('convoys.store') }}">
                    @csrf

                    <div class="field">
                        <label for="name">Convoy name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="120" required>
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="travel_route_id">Route</label>
                        <select id="travel_route_id" name="travel_route_id" required>
                            @foreach ($routes as $route)
                                <option value="{{ $route->id }}" @selected((string) old('travel_route_id', $selectedRoute) === (string) $route->id)>{{ $route->name }} ({{ number_format($route->distance_km, 1) }} km)</option>
                            @endforeach
                        </select>
                        @error('travel_route_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="event_id">Heading to <span class="optional">(optional)</span></label>
                        <select id="event_id" name="event_id">
                            <option value="">No linked gathering</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected((string) old('event_id') === (string) $event->id)>{{ $event->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="departs_at">Departs</label>
                            <input id="departs_at" name="departs_at" type="datetime-local" value="{{ old('departs_at') }}" required>
                            @error('departs_at')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="capacity">Seats</label>
                            <input id="capacity" name="capacity" type="number" min="2" max="200" value="{{ old('capacity', 10) }}" required>
                            @error('capacity')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="notes">Meeting point and notes <span class="optional">(optional)</span></label>
                        <textarea id="notes" name="notes" rows="4" maxlength="2000">{{ old('notes') }}</textarea>
                        @error('notes')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <button class="button" type="submit">Create convoy</button>
                </form>
            @endif
        </section>
    </div>
@endsection
