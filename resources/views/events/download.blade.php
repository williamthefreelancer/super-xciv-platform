@extends('layouts.app')

@section('title', 'Download Tickets | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Download</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Tickets</p>
                <h1 class="event-ticket-title">Download Tickets</h1>
                <p class="event-ticket-subtitle">Ticket downloads</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic.confirmation', $slug) }}" class="button button-quiet">Back to Confirmation</a>
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to Event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Ticket Downloads</h2>
                </header>
                <div class="event-checkout-items">
                    <p style="color: #f5f5f5; line-height: 1.6;">All events are round trip for Harris Teeter parking lot in Harrisburg, NC to Wyoma Square in Lynn, MA. Tickets are undisclosed after payment for confirmation details for download.</p>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Event</h2>
                    </header>
                    <p class="event-ticket-side-text">{{ $event['title'] }}</p>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
