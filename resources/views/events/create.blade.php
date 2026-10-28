@extends('layouts.app')

@section('title', 'Host a gathering | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page" aria-labelledby="create-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> For hosts</p>
            <h1 class="page-title" id="create-title">Host a gathering</h1>

            <form class="form" method="POST" action="{{ route('events.store') }}">
                @csrf

                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" maxlength="120" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="summary">One-line summary</label>
                    <input id="summary" name="summary" type="text" value="{{ old('summary') }}" maxlength="255" required>
                    @error('summary')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="description">Details <span class="optional">(optional)</span></label>
                    <textarea id="description" name="description" rows="6" maxlength="5000">{{ old('description') }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="venue">Venue</label>
                        <input id="venue" name="venue" type="text" value="{{ old('venue') }}" required>
                        @error('venue')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="city">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city', auth()->user()->city) }}" required>
                        @error('city')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="starts_at">Starts</label>
                        <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at') }}" required>
                        @error('starts_at')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="capacity">Capacity</label>
                        <input id="capacity" name="capacity" type="number" min="2" max="500" value="{{ old('capacity', 20) }}" required>
                        @error('capacity')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                @if ($communities->isNotEmpty())
                    <div class="field">
                        <label for="community_id">Community <span class="optional">(optional)</span></label>
                        <select id="community_id" name="community_id">
                            <option value="">None</option>
                            @foreach ($communities as $community)
                                <option value="{{ $community->id }}" @selected((string) old('community_id') === (string) $community->id)>{{ $community->name }}</option>
                            @endforeach
                        </select>
                        @error('community_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                @endif

                <button class="button" type="submit">Publish gathering</button>
            </form>
        </section>
    </div>
@endsection
