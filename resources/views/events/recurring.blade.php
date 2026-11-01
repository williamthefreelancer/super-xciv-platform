@extends('layouts.app')

@section('title', 'Recurring Events | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Recurring Events</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Schedule</p>
                <h1 class="event-ticket-title">Recurring Events</h1>
                <p class="event-ticket-subtitle">Create repeating event series.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Series Configuration</h2>
                </header>
                <form class="event-form">
                    <div class="field">
                        <label>Frequency</label>
                        <select>
                            <option>Weekly</option>
                            <option>Biweekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Start Date</label>
                        <input type="date">
                    </div>
                    <div class="field">
                        <label>End Date (optional)</label>
                        <input type="date">
                    </div>
                    <button type="button" class="button">Create Series</button>
                </form>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Upcoming in Series</h2>
                    </header>
                    <div class="event-simple-list">
                        <div class="event-simple-item">Next occurrence: TBA</div>
                        <div class="event-simple-item">Series status: Not configured</div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
