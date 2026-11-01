@extends('layouts.app')

@section('title', 'Tickets | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event-ticket.css') }}">
@endpush

@section('content')
<section class="event-ticket-hero">
    <div class="event-ticket-hero-bg" aria-hidden="true"></div>
    <div class="page-wrap">
        <nav class="event-ticket-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('events.dynamic', $slug) }}">{{ $event['title'] }}</a>
            <span aria-hidden="true">/</span>
            <span>Tickets</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Ticketing</p>
                <h1 class="event-ticket-title">Tickets</h1>
                <p class="event-ticket-subtitle">{{ $event['title'] }} &mdash; Manage ticket types and availability.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic.checkout', $slug) }}" class="button">Checkout</a>
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Ticket Types</h2>
                    <span class="event-ticket-badge">Dynamic</span>
                </header>
                <div class="event-ticket-list">
                    <article class="event-ticket-item">
                        <div class="event-ticket-info">
                            <h3 class="event-ticket-name">General Admission</h3>
                            <p class="event-ticket-meta">Standard entry ticket</p>
                        </div>
                        <div class="event-ticket-price">$50.00</div>
                        <div class="event-ticket-qty">Available: 100</div>
                    </article>
                    <article class="event-ticket-item">
                        <div class="event-ticket-info">
                            <h3 class="event-ticket-name">VIP Access</h3>
                            <p class="event-ticket-meta">Priority entry &amp; perks</p>
                        </div>
                        <div class="event-ticket-price">$120.00</div>
                        <div class="event-ticket-qty">Available: 25</div>
                    </article>
                    <article class="event-ticket-item">
                        <div class="event-ticket-info">
                            <h3 class="event-ticket-name">Group Pass</h3>
                            <p class="event-ticket-meta">For groups of 4+</p>
                        </div>
                        <div class="event-ticket-price">$180.00</div>
                        <div class="event-ticket-qty">Available: 20</div>
                    </article>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Quick Actions</h2>
                    </header>
                    <div class="event-ticket-actions">
                        <a href="{{ route('events.dynamic.checkout', $slug) }}" class="event-ticket-action">Start Checkout</a>
                        <a href="{{ route('events.dynamic.pos', $slug) }}" class="event-ticket-action">Point of Sale</a>
                        <a href="{{ route('events.dynamic.checkin', $slug) }}" class="event-ticket-action">Check-in</a>
                    </div>
                </section>

                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Event Tools</h2>
                    </header>
                    <div class="event-ticket-tools">
                        <a href="{{ route('events.dynamic.venue', $slug) }}">Venue Designer</a>
                        <a href="{{ route('events.dynamic.recurring', $slug) }}">Recurring Events</a>
                        <a href="{{ route('events.dynamic.fields', $slug) }}">Custom Attendee Fields</a>
                        <a href="{{ route('events.dynamic.calendar', $slug) }}">Calendar</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
