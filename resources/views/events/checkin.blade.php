@extends('layouts.app')

@section('title', 'Check-in | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Check-in</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Attendees</p>
                <h1 class="event-ticket-title">Check-in</h1>
                <p class="event-ticket-subtitle">Scan tickets and check in attendees.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Check-in Scanner</h2>
                </header>
                <div class="event-checkin">
                    <div class="event-checkin-scanner">
                        <div class="event-checkin-placeholder">QR/Barcode Scanner</div>
                    </div>
                    <div class="event-checkin-manual">
                        <div class="field">
                            <label>Search by name or ticket code</label>
                            <input type="text" placeholder="Enter name or code">
                        </div>
                        <button class="button">Search</button>
                    </div>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Stats</h2>
                    </header>
                    <div class="event-checkin-stats">
                        <div class="event-checkin-stat">
                            <span>Total</span>
                            <span>0</span>
                        </div>
                        <div class="event-checkin-stat">
                            <span>Checked In</span>
                            <span>0</span>
                        </div>
                        <div class="event-checkin-stat">
                            <span>Remaining</span>
                            <span>0</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
