@extends('layouts.app')

@section('title', 'Schedule a broadcast | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page form-narrow" aria-labelledby="broadcast-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Webcast and audio</p>
            <h1 class="page-title" id="broadcast-title">Schedule a broadcast</h1>

            <form class="form" method="POST" action="{{ route('broadcasts.store') }}">
                @csrf

                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" maxlength="150" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="description">Description <span class="optional">(optional)</span></label>
                    <textarea id="description" name="description" rows="4" maxlength="2000">{{ old('description') }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="type">Format</label>
                        <select id="type" name="type" required>
                            @foreach (\App\Models\Broadcast::TYPES as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', 'video') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="scheduled_at">Starts <span class="optional">(optional)</span></label>
                        <input id="scheduled_at" name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at') }}">
                        @error('scheduled_at')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="field">
                    <label for="stream_url">Stream URL <span class="optional">(can be added when you go live)</span></label>
                    <input id="stream_url" name="stream_url" type="url" value="{{ old('stream_url') }}" placeholder="https://&hellip;/stream.m3u8, MP3/Icecast, YouTube or Vimeo">
                    @error('stream_url')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                @if ($events->isNotEmpty())
                    <div class="field">
                        <label for="event_id">Linked gathering <span class="optional">(optional)</span></label>
                        <select id="event_id" name="event_id">
                            <option value="">None</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected((string) old('event_id') === (string) $event->id)>{{ $event->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                @endif

                <button class="button" type="submit">Schedule broadcast</button>
            </form>
        </section>
    </div>
@endsection
