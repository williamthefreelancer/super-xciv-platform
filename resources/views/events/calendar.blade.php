@extends('layouts.app')

@section('title', 'Calendar | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Calendar</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Schedule</p>
                <h1 class="event-ticket-title">Calendar</h1>
                <p class="event-ticket-subtitle">Event schedule and timeline.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Event Calendar</h2>
                </header>
                <div class="event-calendar">
                    <div class="event-calendar-header">
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                        <div>Sun</div>
                    </div>
                    <div class="event-calendar-grid">
                        @for ($i = 1; $i <= 28; $i++)
                            <div class="event-calendar-day {{ $i == 15 ? 'event-calendar-day-active' : '' }}">
                                <span>{{ $i }}</span>
                                @if ($i == 15)
                                    <div class="event-calendar-event">Event Day</div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Schedule</h2>
                    </header>
                    <div class="event-simple-list">
                        <div class="event-simple-item">Event Date: TBA</div>
                        <div class="event-simple-item">Time: TBA</div>
                        <div class="event-simple-item">Add to calendar coming soon</div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
