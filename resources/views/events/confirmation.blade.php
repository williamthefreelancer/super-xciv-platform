@extends('layouts.app')

@section('title', 'Confirmation | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Confirmation</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Confirmation</p>
                <h1 class="event-ticket-title">Confirmation</h1>
                <p class="event-ticket-subtitle">Your order confirmation</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic.download', $slug) }}" class="button">Download Tickets</a>
                <a href="{{ route('events.dynamic', $slug) }}" class="button button-quiet">Back to Event</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Order Confirmation</h2>
                </header>
                <div class="event-checkout-items">
                    <p style="color: #f5f5f5; line-height: 1.6;">All ticket details are undisclosed after payment for confirmation details for download.</p>
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
