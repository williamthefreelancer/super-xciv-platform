@extends('layouts.app')

@section('title', 'Venue Designer | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Venue Designer</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Venue</p>
                <h1 class="event-ticket-title">Venue Designer</h1>
                <p class="event-ticket-subtitle">Design layouts, sections, and seating.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Layout Designer</h2>
                </header>
                <div class="event-venue-designer">
                    <div class="event-venue-toolbar">
                        <button class="event-venue-tool">Add Section</button>
                        <button class="event-venue-tool">Add Table</button>
                        <button class="event-venue-tool">Add Seat</button>
                        <button class="event-venue-tool">Save Layout</button>
                    </div>
                    <div class="event-venue-canvas">
                        <div class="event-venue-placeholder">Venue layout canvas (drag &amp; drop designer)</div>
                    </div>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Sections</h2>
                    </header>
                    <div class="event-venue-list">
                        <div class="event-venue-list-item">Main Floor</div>
                        <div class="event-venue-list-item">VIP Section</div>
                        <div class="event-venue-list-item">Balcony</div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
