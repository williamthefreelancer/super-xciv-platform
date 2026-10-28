@extends('layouts.app')

@section('title', 'About | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-narrow" aria-labelledby="about-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> About XCIV</p>
            <h1 class="page-title" id="about-title">Hospitality, <em>in person.</em></h1>
            <div class="prose">
                <p>XCIV is a community for the people behind hospitality, nightlife, clubs, and live events. We believe the strongest professional relationships begin in a good room, with good hosts and a reason to talk.</p>
                <p>Every gathering on XCIV is hosted by a member and has a fixed number of seats, so the conversation stays worth having. Online is where those relationships continue, not where they start.</p>
            </div>
            <p class="section-more"><a class="text-link" href="{{ route('events.index') }}">See what's coming up <span aria-hidden="true">&#8594;</span></a></p>
        </section>
    </div>
@endsection
