@extends('layouts.app')

@section('title', 'Checkout | ' . ($event['title'] ?? 'Event') . ' | ' . config('app.name'))

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
            <a href="{{ route('events.dynamic.tickets', $slug) }}">Tickets</a>
            <span aria-hidden="true">/</span>
            <span>Checkout</span>
        </nav>
        <header class="event-ticket-header">
            <div>
                <p class="event-ticket-eyebrow">Checkout</p>
                <h1 class="event-ticket-title">Checkout</h1>
                <p class="event-ticket-subtitle">Complete your ticket purchase.</p>
            </div>
            <div class="event-ticket-header-actions">
                <a href="{{ route('events.dynamic.tickets', $slug) }}" class="button button-quiet">Back to tickets</a>
            </div>
        </header>

        <div class="event-ticket-grid">
            <section class="event-ticket-card">
                <header class="event-ticket-card-header">
                    <h2 class="event-ticket-card-title">Order Summary</h2>
                </header>
                <form method="POST" action="{{ route('events.dynamic.checkout.process', $slug) }}" class="event-checkout-form">
                    @csrf
                    <div class="event-checkout-items">
                        <div class="event-checkout-item">
                            <div>
                                <div class="event-checkout-name">General Admission</div>
                                <div class="event-checkout-meta">Qty: 1</div>
                            </div>
                            <div class="event-checkout-price">$50.00</div>
                        </div>
                    </div>
                    <div class="event-checkout-total">
                        <span>Total</span>
                        <span>$50.00</span>
                    </div>
                    <div class="event-checkout-fields">
                        <div class="field">
                            <label for="full_name">Full Name</label>
                            <input id="full_name" name="full_name" type="text" placeholder="Enter your full name" required>
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" placeholder="you@example.com" required>
                        </div>
                        <div class="field">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="tel" placeholder="+1 555 123 4567">
                        </div>
                    </div>
                    <button type="submit" class="button button-block">Place Order</button>
                </form>
            </section>

            <aside class="event-ticket-side">
                <section class="event-ticket-card event-ticket-card-compact">
                    <header class="event-ticket-card-header">
                        <h2 class="event-ticket-card-title">Payment</h2>
                    </header>
                    <p class="event-ticket-side-text">Payment processing is configured for this event. Complete the form to place your order.</p>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
