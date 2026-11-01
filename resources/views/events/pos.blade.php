@extends('layouts.app')

@section('title', 'Point of Sale | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <span>Point of Sale</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">POS</p>
                <h1 class="event-ticket-title">Point of Sale</h1>
                <p class="event-ticket-subtitle">On-site ticket sales and payments.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic.tickets', $slug) }}" class="button button-quiet">Back to tickets</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">POS Terminal</h2>
                    <span class="event-ticket-badge">Active</span>
                </header>
                <div class="event-pos-grid">
                    <div class="event-pos-products">
                        <h3 class="event-pos-section-title">Products</h3>
                        <div class="event-pos-product-list">
                            <button class="event-pos-product">General Admission - $50</button>
                            <button class="event-pos-product">VIP Access - $120</button>
                            <button class="event-pos-product">Group Pass - $180</button>
                            <button class="event-pos-product">Merch - $25</button>
                        </div>
                    </div>
                    <div class="event-pos-cart">
                        <h3 class="event-pos-section-title">Cart</h3>
                        <div class="event-pos-cart-items">
                            <div class="event-pos-cart-item">
                                <span>General Admission</span>
                                <span>$50.00</span>
                            </div>
                        </div>
                        <div class="event-pos-cart-total">
                            <span>Total</span>
                            <span>$50.00</span>
                        </div>
                        <button class="button button-block">Charge</button>
                    </div>
                </div>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">POS Tools</h2>
                    </header>
                    <div class="event-ticket-tools">
                        <a href="{{ route('events.dynamic.checkin', $slug) }}">Check-in</a>
                        <a href="{{ route('events.dynamic.venue', $slug) }}">Venue Designer</a>
                        <a href="{{ route('events.dynamic.calendar', $slug) }}">Calendar</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
