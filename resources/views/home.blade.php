@extends('layouts.app')

@section('title', 'Hospitality, in person | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="intro-grid" id="gatherings" aria-labelledby="intro-title">
            <div class="intro-copy">
                <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> A network for people who host</p>
                <h1 id="intro-title">The best connections happen <em>in person.</em></h1>
                <p class="intro-description">A trusted community for the people behind hospitality, nightlife, clubs, and live events. Less noise online. More good people in the room.</p>
                <a class="text-link" href="{{ route('events.index') }}">Explore the gathering calendar <span aria-hidden="true">&#8594;</span></a>
            </div>

            <figure class="gathering-image">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1400&q=85" alt="A warmly lit restaurant prepared for an evening gathering" fetchpriority="high">
                <figcaption>
                    <span class="image-caption-label">GOOD ROOMS. GOOD PEOPLE.</span>
                    <span class="image-caption-note">The conversation is better around a table.</span>
                </figcaption>
            </figure>
        </section>

        <section class="gatherings-section" id="upcoming" aria-labelledby="gatherings-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Start with a handshake</p>
                    <h2 id="gatherings-title">Gatherings</h2>
                </div>
                <span class="section-index">01 / THE CALENDAR</span>
            </div>

            @forelse ($events as $event)
                @if ($loop->first)
                    <div class="event-grid">
                @endif
                <x-event-card :event="$event" />
                @if ($loop->last)
                    </div>
                    <p class="section-more"><a class="text-link" href="{{ route('events.index') }}">See every gathering <span aria-hidden="true">&#8594;</span></a></p>
                @endif
            @empty
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">&#10022;</span>
                    <div>
                        <h3>The first room is taking shape.</h3>
                        <p>We’re building the opening calendar with hospitality operators. The aim is simple: useful introductions, thoughtful hosts, and a reason to meet face to face.</p>
                    </div>
                    <span class="empty-state-note">COMING INTO FOCUS</span>
                </div>
            @endforelse
        </section>

        <section class="operator-section" id="operators" aria-labelledby="operators-title">
            <div class="operator-heading">
                <p class="eyebrow">A shared table, many disciplines</p>
                <h2 id="operators-title">For the people<br>who make a night <em>matter.</em></h2>
            </div>
            <div class="operator-list">
                <article class="operator-item">
                    <span class="operator-number">01</span>
                    <div><h3>Event makers</h3><p>Independent producers and event management teams.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
                <article class="operator-item">
                    <span class="operator-number">02</span>
                    <div><h3>Nightlife &amp; clubs</h3><p>Club operators, talent teams, and nightlife companies.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
                <article class="operator-item">
                    <span class="operator-number">03</span>
                    <div><h3>Hospitality</h3><p>Venue groups and the teams that make guests feel welcome.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
            </div>
        </section>
    </div>
@endsection