@extends('layouts.app')

@section('title', 'Start a community | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page form-narrow" aria-labelledby="community-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Bring people together</p>
            <h1 class="page-title" id="community-title">Start a community</h1>

            <form class="form" method="POST" action="{{ route('communities.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="80" required>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="summary">One-line summary</label>
                    <input id="summary" name="summary" type="text" value="{{ old('summary') }}" maxlength="255" required>
                    @error('summary')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="city">City <span class="optional">(optional)</span></label>
                    <input id="city" name="city" type="text" value="{{ old('city', auth()->user()->city) }}">
                    @error('city')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="description">About <span class="optional">(optional)</span></label>
                    <textarea id="description" name="description" rows="6" maxlength="5000">{{ old('description') }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <button class="button" type="submit">Create community</button>
            </form>
        </section>
    </div>
@endsection
