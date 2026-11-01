@extends('layouts.app')

@section('title', 'Dashboard | ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<section class="dashboard-hero">
    <div class="dashboard-hero-bg" aria-hidden="true"></div>
    <div class="dashboard-noise" aria-hidden="true"></div>
    <div class="page-wrap">
        <header class="dashboard-header">
            <div>
                <p class="dashboard-eyebrow">SUPR XCIV</p>
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back{{ auth()->user() ? ', ' . auth()->user()->name : '' }}. Your curated access to the community's events.</p>
            </div>
            <div class="dashboard-header-actions">
                <a href="{{ route('home') }}" class="button dashboard-btn">Back to home</a>
            </div>
        </header>

        <div class="dashboard-content">
            <section class="events-section" aria-labelledby="upcoming-events-title">
                <header class="section-header">
                    <div>
                        <p class="section-eyebrow">Discover</p>
                        <h2 id="upcoming-events-title" class="section-title">Upcoming Events</h2>
                        <p class="section-description">Curated experiences across hospitality, adventure, and community</p>
                    </div>
                    <span class="events-count">{{ count([1,2,3,4]) }} Events</span>
                </header>

                <div class="events-grid">
                    <article class="event-card event-card-featured">
                        <div class="event-card-bg" style="background-image: url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1200&h=800&fit=crop');" aria-hidden="true"></div>
                        <div class="event-card-overlay"></div>
                        <div class="event-card-content">
                            <div class="event-card-header">
                                <span class="event-badge event-badge-featured">Featured</span>
                                <span class="event-price">$97.00</span>
                            </div>
                            <div class="event-card-body">
                                <h3 class="event-card-title">The Invite Only Social Immerse Event</h3>
                                <p class="event-card-subtitle">An intimate, invite-only gathering designed for connection and conversation</p>
                                <div class="event-card-meta">
                                    <span class="meta-item">30.07.2026 • 22:00</span>
                                    <span class="meta-item">Private Venue • USA</span>
                                    <span class="meta-item">Invite-Only</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <a href="{{ route('events.dynamic', 'invite-only-social-immerse') }}" class="event-card-btn">View Event</a>
                            </div>
                        </div>
                    </article>

                    <article class="event-card">
                        <div class="event-card-bg" style="background-image: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop');" aria-hidden="true"></div>
                        <div class="event-card-overlay"></div>
                        <div class="event-card-content">
                            <div class="event-card-header">
                                <span class="event-badge">Adventure</span>
                                <span class="event-price">$97.00</span>
                            </div>
                            <div class="event-card-body">
                                <h3 class="event-card-title">800 Plus Journeys</h3>
                                <p class="event-card-subtitle">A multi-stop journey connecting cities through shared experiences</p>
                                <div class="event-card-meta">
                                    <span class="meta-item">07.08.2026 • 18:00</span>
                                    <span class="meta-item">Route-based • USA</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <a href="{{ route('events.dynamic', '800-plus-journeys') }}" class="event-card-btn">View Event</a>
                            </div>
                        </div>
                    </article>

                    <article class="event-card">
                        <div class="event-card-bg" style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&h=600&fit=crop');" aria-hidden="true"></div>
                        <div class="event-card-overlay"></div>
                        <div class="event-card-content">
                            <div class="event-card-header">
                                <span class="event-badge">Community</span>
                                <span class="event-price">$97.00</span>
                            </div>
                            <div class="event-card-body">
                                <h3 class="event-card-title">Township Adventures</h3>
                                <p class="event-card-subtitle">Local adventures rooted in community and culture</p>
                                <div class="event-card-meta">
                                    <span class="meta-item">15.08.2026 • 14:00</span>
                                    <span class="meta-item">Township Locations • USA</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <a href="{{ route('events.dynamic', 'township-adventures') }}" class="event-card-btn">View Event</a>
                            </div>
                        </div>
                    </article>

                    <article class="event-card">
                        <div class="event-card-bg" style="background-image: url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop');" aria-hidden="true"></div>
                        <div class="event-card-overlay"></div>
                        <div class="event-card-content">
                            <div class="event-card-header">
                                <span class="event-badge">Party</span>
                                <span class="event-price">$97.00</span>
                            </div>
                            <div class="event-card-body">
                                <h3 class="event-card-title">Badlands BRA Party Bus</h3>
                                <p class="event-card-subtitle">A rolling celebration through the Badlands</p>
                                <div class="event-card-meta">
                                    <span class="meta-item">22.08.2026 • 23:00</span>
                                    <span class="meta-item">Party Bus • USA</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <a href="{{ route('events.dynamic', 'badlands-bra-party-bus') }}" class="event-card-btn">View Event</a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <aside class="dashboard-sidebar">
                <section class="sidebar-card" aria-labelledby="quick-actions-title">
                    <header class="sidebar-card-header">
                        <h2 id="quick-actions-title" class="sidebar-card-title">Quick Actions</h2>
                    </header>
                    <div class="sidebar-actions">
                        <a href="{{ route('home') }}#join" class="sidebar-action-link">
                            <span class="action-icon">→</span>
                            <span>Apply for Admission</span>
                        </a>
                        <a href="{{ route('events.dynamic', 'invite-only-social-immerse') }}" class="sidebar-action-link">
                            <span class="action-icon">★</span>
                            <span>View Featured Event</span>
                        </a>
                        <a href="{{ route('home') }}" class="sidebar-action-link">
                            <span class="action-icon">←</span>
                            <span>Return to Home</span>
                        </a>
                    </div>
                </section>

                <section class="sidebar-card" aria-labelledby="status-title">
                    <header class="sidebar-card-header">
                        <h2 id="status-title" class="sidebar-card-title">Member Status</h2>
                    </header>
                    <div class="status-content">
                        <div class="status-indicator">
                            <span class="status-dot"></span>
                            <span class="status-text">Active</span>
                        </div>
                        <p class="status-description">You're signed in and ready to explore the SUPR XCIV community.</p>
                    </div>
                </section>

                <section class="sidebar-card sidebar-card-accent" aria-labelledby="community-title">
                    <header class="sidebar-card-header">
                        <h2 id="community-title" class="sidebar-card-title">Community</h2>
                    </header>
                    <div class="community-content">
                        <p class="community-text">Hospitality, in person. A community shaped by the people who bring people together.</p>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
