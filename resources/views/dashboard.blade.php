@extends('layouts.app')

@section('title', 'Dashboard | ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<section class="dashboard-hero">
    <div class="dashboard-hero-bg" aria-hidden="true"></div>
    <div class="page-wrap">
        <header class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back{{ auth()->user() ? ', ' . auth()->user()->name : '' }}. Manage your events, gatherings, and access.</p>
            </div>
            <div class="dashboard-header-actions">
                <a href="{{ route('home') }}" class="button button-quiet">Back to home</a>
            </div>
        </header>

        <div class="dashboard-grid">
            <section class="dashboard-card" aria-labelledby="upcoming-events-title">
                <header class="dashboard-card-header">
                    <h2 id="upcoming-events-title" class="dashboard-card-title">Upcoming Events</h2>
                    <span class="dashboard-card-badge">Featured</span>
                </header>
                <p class="dashboard-card-description">Explore the latest invite-only and special events.</p>
                <div class="dashboard-event-list">
                    <article class="dashboard-event-item">
                        <div class="dashboard-event-info">
                            <h3 class="dashboard-event-name">The Invite Only Social Immerse Event</h3>
                            <p class="dashboard-event-meta">Invite-only &middot; Social immersive experience</p>
                        </div>
                        <a href="{{ route('events.dynamic', 'invite-only-social-immerse') }}" class="button button-small">View event</a>
                    </article>
                    <article class="dashboard-event-item">
                        <div class="dashboard-event-info">
                            <h3 class="dashboard-event-name">The 800 Plus Journeys Event</h3>
                            <p class="dashboard-event-meta">Adventure &middot; Multi-stop journey</p>
                        </div>
                        <a href="{{ route('events.dynamic', '800-plus-journeys') }}" class="button button-small">View event</a>
                    </article>
                    <article class="dashboard-event-item">
                        <div class="dashboard-event-info">
                            <h3 class="dashboard-event-name">The Township Adventures Event</h3>
                            <p class="dashboard-event-meta">Local experiences &middot; Community driven</p>
                        </div>
                        <a href="{{ route('events.dynamic', 'township-adventures') }}" class="button button-small">View event</a>
                    </article>
                    <article class="dashboard-event-item">
                        <div class="dashboard-event-info">
                            <h3 class="dashboard-event-name">The Badlands BRA Party Bus Event</h3>
                            <p class="dashboard-event-meta">Party &middot; On-the-go celebration</p>
                        </div>
                        <a href="{{ route('events.dynamic', 'badlands-bra-party-bus') }}" class="button button-small">View event</a>
                    </article>
                </div>
            </section>

            <aside class="dashboard-side">
                <section class="dashboard-card dashboard-card-compact" aria-labelledby="quick-actions-title">
                    <header class="dashboard-card-header">
                        <h2 id="quick-actions-title" class="dashboard-card-title">Quick Actions</h2>
                    </header>
                    <div class="dashboard-quick-actions">
                        <a href="{{ route('home') }}#join" class="dashboard-quick-link">Apply for admission</a>
                        <a href="{{ route('events.dynamic', 'invite-only-social-immerse') }}" class="dashboard-quick-link">View featured event</a>
                        <a href="#" class="dashboard-quick-link">Manage profile</a>
                    </div>
                </section>

                <section class="dashboard-card dashboard-card-compact" aria-labelledby="status-title">
                    <header class="dashboard-card-header">
                        <h2 id="status-title" class="dashboard-card-title">Status</h2>
                    </header>
                    <p class="dashboard-status-text">You're signed in and ready to explore the community.</p>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
