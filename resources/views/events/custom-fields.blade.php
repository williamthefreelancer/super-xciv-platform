@extends('layouts.app')

@section('title', 'Custom Attendee Fields | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Custom Attendee Fields</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Attendees</p>
                <h1 class="event-ticket-title">Custom Attendee Fields</h1>
                <p class="event-ticket-subtitle">Collect additional information from attendees.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Field Builder</h2>
                </header>
                <div class="event-field-builder">
                    <div class="event-field-row">
                        <input type="text" placeholder="Field label (e.g., Dietary Restrictions)">
                        <select>
                            <option>Text</option>
                            <option>Select</option>
                            <option>Checkbox</option>
                            <option>Number</option>
                        </select>
                        <button class="button button-small">Add Field</button>
                    </div>
                    <div class="event-field-list">
                        <div class="event-field-list-item">No custom fields added yet.</div>
                    </div>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Preview</h2>
                    </header>
                    <p class="event-ticket-side-text">Custom fields will appear on the checkout/registration form.</p>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
