@extends('layouts.app')

@section('title', ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event-dynamic.css') }}">
@endpush

@section('content')
<section class="event-dynamic-hero">
    <div class="event-dynamic-hero-bg" aria-hidden="true"></div>
    <div class="page-wrap">
        <nav class="event-dynamic-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <span aria-hidden="true">/</span>
            <span>Events</span>
            <span aria-hidden="true">/</span>
            <span>{{ $event['title'] ?? 'Event' }}</span>
        </nav>
        <header class="event-dynamic-header">
            <div>
                <p class="event-dynamic-eyebrow">Event Details</p>
                <h1 class="event-dynamic-title">{{ $event['title'] ?? 'Event' }}</h1>
                <p class="event-dynamic-subtitle">{{ $event['subtitle'] ?? '' }}</p>
            </div>
            <div class="event-dynamic-header-actions">
                <a href="{{ route('events.dynamic.tickets', $slug) }}" class="button">Get Tickets</a>
                <a href="{{ route('dashboard') }}" class="button button-quiet">Back to dashboard</a>
            </div>
        </header>

        <div class="event-dynamic-grid">
            <article class="event-dynamic-card">
                <header class="event-dynamic-card-header">
                    <h2 class="event-dynamic-card-title">Overview</h2>
                </header>
                <p class="event-dynamic-description">{{ $event['description'] ?? '' }}</p>
                @if (!empty($event['details']))
                    <div class="event-dynamic-details">
                        <h3 class="event-dynamic-details-title">Event Information</h3>
                        <dl class="event-dynamic-details-list">
                            @foreach ($event['details'] as $label => $value)
                                <div class="event-dynamic-details-item">
                                    <dt>{{ $label }}</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif
            </article>

            <aside class="event-dynamic-side">
                <section class="event-dynamic-card event-dynamic-card-compact">
                    <header class="event-dynamic-card-header">
                        <h2 class="event-dynamic-card-title">Event Details</h2>
                    </header>
                    <dl class="event-dynamic-meta-list">
                        <div class="event-dynamic-meta-item">
                            <dt>Date</dt>
                            <dd>{{ $event['date'] ?? 'TBA' }}</dd>
                        </div>
                        <div class="event-dynamic-meta-item">
                            <dt>Venue</dt>
                            <dd>{{ $event['venue'] ?? 'TBA' }}</dd>
                        </div>
                        <div class="event-dynamic-meta-item">
                            <dt>Location</dt>
                            <dd>{{ $event['city'] ?? 'TBA' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="event-dynamic-card event-dynamic-card-compact">
                    <header class="event-dynamic-card-header">
                        <h2 class="event-dynamic-card-title">Ticketing &amp; Management</h2>
                    </header>
                    <div class="event-feature-grid">
                        <a href="{{ route('events.dynamic.tickets', $slug) }}" class="event-feature-link">Tickets</a>
                        <a href="{{ route('events.dynamic.checkout', $slug) }}" class="event-feature-link">Checkout</a>
                        <a href="{{ route('events.dynamic.pos', $slug) }}" class="event-feature-link">Point of Sale (POS)</a>
                        <a href="{{ route('events.dynamic.venue', $slug) }}" class="event-feature-link">Venue Designer</a>
                        <a href="{{ route('events.dynamic.recurring', $slug) }}" class="event-feature-link">Recurring Events</a>
                        <a href="{{ route('events.dynamic.fields', $slug) }}" class="event-feature-link">Custom Attendee Fields</a>
                        <a href="{{ route('events.dynamic.checkin', $slug) }}" class="event-feature-link">Check-in</a>
                        <a href="{{ route('events.dynamic.calendar', $slug) }}" class="event-feature-link">Calendar</a>
                    </div>
                </section>

                <section class="event-dynamic-card event-dynamic-card-compact">
                    <header class="event-dynamic-card-header">
                        <h2 class="event-dynamic-card-title">Registration</h2>
                    </header>
                    <p class="event-dynamic-side-text">Get started with ticketing for this event. Manage sales, check-in, and event setup from the tools above.</p>
                    <div class="event-dynamic-side-actions">
                        <a href="{{ route('events.dynamic.tickets', $slug) }}" class="button button-block">View Tickets</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
