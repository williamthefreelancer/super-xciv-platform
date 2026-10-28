@extends('layouts.app')

@section('title', 'Draw a route | ' . config('app.name'))

@section('content')
    @php
        $initialWaypoints = old('waypoints', '[]');
        $initialWaypoints = is_array($initialWaypoints) ? json_encode($initialWaypoints) : $initialWaypoints;
    @endphp
    <div class="page-wrap">
        <section class="page-section form-page" aria-labelledby="route-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Route builder</p>
            <h1 class="page-title" id="route-title">Draw a route</h1>

            <form class="form" method="POST" action="{{ route('routes.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="120" required>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="description">Description <span class="optional">(optional)</span></label>
                    <textarea id="description" name="description" rows="3" maxlength="2000">{{ old('description') }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <span class="label-text">Stops <span class="optional">(click the map to add the start, stops and finish in order)</span></span>
                    <div class="map" data-map data-editable data-tile-url="{{ config('services.map.tile_url') }}" data-input="#waypoints" data-list="#waypoint-list" role="application" aria-label="Route map. Click to add a stop."></div>
                    <input type="hidden" id="waypoints" name="waypoints" value="{{ $initialWaypoints }}">
                    <ol id="waypoint-list" class="waypoint-list"></ol>
                    <div class="map-actions">
                        <button class="button button-small button-quiet" type="button" data-map-undo>Undo last stop</button>
                        <button class="button button-small button-quiet" type="button" data-map-clear>Clear all</button>
                    </div>
                    @if ($errors->has('waypoints') || $errors->get('waypoints.*'))
                        <p class="field-error">Add at least two valid stops by clicking the map.</p>
                    @endif
                </div>

                <button class="button" type="submit">Save route</button>
            </form>
        </section>
    </div>
@endsection
