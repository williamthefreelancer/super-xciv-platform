@extends('layouts.app')

@section('title', $event->title . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section event-detail">
            <div class="event-detail-main">
                <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> {{ $event->starts_at->format('l j F Y · g:i A') }}</p>
                <h1 class="page-title">{{ $event->title }}</h1>
                <p class="intro-description">{{ $event->summary }}</p>

                @if ($event->description)
                    <div class="prose">
                        @foreach (preg_split('/\R{2,}/', $event->description) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                @endif

                @if ($attendees->isNotEmpty())
                    <section class="attendee-list" aria-labelledby="attendees-title">
                        <h2 id="attendees-title">Registered ({{ $attendees->count() }})</h2>
                        <ul>
                            @foreach ($attendees as $attendee)
                                <li>{{ $attendee->name }}@if ($attendee->organization_name) <span>{{ $attendee->organization_name }}</span>@endif</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($broadcasts->isNotEmpty())
                    <section class="attendee-list" aria-labelledby="broadcasts-title">
                        <h2 id="broadcasts-title">Broadcasts</h2>
                        <ul>
                            @foreach ($broadcasts as $broadcast)
                                <li>
                                    <a href="{{ route('broadcasts.show', $broadcast) }}">{{ $broadcast->title }}</a>
                                    <span>{{ $broadcast->isLive() ? 'Live now' : ucfirst($broadcast->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($convoys->isNotEmpty())
                    <section class="attendee-list" aria-labelledby="convoys-title">
                        <h2 id="convoys-title">Convoys to this gathering</h2>
                        <ul>
                            @foreach ($convoys as $convoy)
                                <li>
                                    <a href="{{ route('convoys.show', $convoy) }}">{{ $convoy->name }}</a>
                                    <span>{{ $convoy->departs_at->format('D j M · g:i A') }} · {{ $convoy->members_count }} joined</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside class="event-detail-side" aria-label="Gathering details">
                <dl class="detail-list">
                    <div><dt>Venue</dt><dd>{{ $event->venue }}</dd></div>
                    <div><dt>City</dt><dd>{{ $event->city }}</dd></div>
                    @if ($event->community)
                        <div><dt>Community</dt><dd><a href="{{ route('communities.show', $event->community) }}">{{ $event->community->name }}</a></dd></div>
                    @endif
                    <div><dt>Host</dt><dd>{{ $event->host->name }}@if ($event->host->organization_name), {{ $event->host->organization_name }}@endif</dd></div>
                    <div><dt>Seats</dt><dd>{{ $event->registrations_count }} of {{ $event->capacity }} taken</dd></div>
                </dl>

                @error('registration')
                    <p class="flash flash-error" role="alert">{{ $message }}</p>
                @enderror

                @if ($event->starts_at->isPast())
                    <p class="form-note">This gathering has taken place.</p>
                @elseif (! auth()->check())
                    <a class="button button-block" href="{{ route('login') }}">Log in to join</a>
                @elseif ($isRegistered)
                    <p class="form-note">You're on the list.</p>
                    <form method="POST" action="{{ route('events.unregister', $event) }}">
                        @csrf
                        @method('DELETE')
                        <button class="button button-block button-quiet" type="submit">Cancel my seat</button>
                    </form>
                @elseif ($event->isFull())
                    <p class="form-note">This gathering is full.</p>
                @else
                    <form method="POST" action="{{ route('events.register', $event) }}">
                        @csrf
                        <button class="button button-block" type="submit">Save my seat</button>
                    </form>
                @endif
            </aside>
        </article>
    </div>
@endsection
