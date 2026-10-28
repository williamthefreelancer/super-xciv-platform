@extends('layouts.app')

@section('title', 'For operators | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="operators-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> A shared table, many disciplines</p>
            <h1 class="page-title" id="operators-title">For the people who make a night <em>matter.</em></h1>
            <p class="intro-description">XCIV brings together the operators behind live events, nightlife, and hospitality. Host a gathering, meet peers face to face, and keep the conversation going afterwards.</p>

            <div class="operator-list operator-list-wide">
                <article class="operator-item">
                    <span class="operator-number">01</span>
                    <div><h3>Event makers</h3><p>Independent producers and event management teams looking for collaborators and venues.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
                <article class="operator-item">
                    <span class="operator-number">02</span>
                    <div><h3>Nightlife &amp; clubs</h3><p>Club operators, talent teams, and nightlife companies sharing what works after dark.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
                <article class="operator-item">
                    <span class="operator-number">03</span>
                    <div><h3>Hospitality</h3><p>Venue groups and the teams that make guests feel welcome.</p></div>
                    <span class="operator-arrow" aria-hidden="true">&#8599;</span>
                </article>
            </div>

            <p class="section-more">
                @auth
                    <a class="button" href="{{ route('events.create') }}">Host a gathering</a>
                @else
                    <a class="button" href="{{ route('register') }}">Join XCIV</a>
                @endauth
            </p>
        </section>
    </div>
@endsection
