@extends('layouts.app')

@section('title', 'Your dashboard | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="dashboard-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">{{ auth()->user()->organization_name }}@if (auth()->user()->sectorLabel()) · {{ auth()->user()->sectorLabel() }}@endif</p>
                    <h1 class="page-title" id="dashboard-title">Hello, {{ auth()->user()->name }}</h1>
                </div>
                <a class="button" href="{{ route('events.create') }}">Host a gathering</a>
            </div>

            <nav class="quick-actions" aria-label="Create">
                <a href="{{ route('communities.create') }}">Start a community</a>
                <a href="{{ route('broadcasts.create') }}">Schedule a broadcast</a>
                <a href="{{ route('routes.create') }}">Draw a route</a>
                <a href="{{ route('convoys.create') }}">Start a convoy</a>
                @can('manage-pages')
                    <a href="{{ route('admin.pages.index') }}">Manage pages</a>
                @endcan
            </nav>

            <h2 class="subheading">Your seats</h2>
            @forelse ($attending as $event)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                    <span>{{ $event->starts_at->format('D j M · g:i A') }} · {{ $event->venue }}</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">You haven't saved a seat yet. <a href="{{ route('events.index') }}">Browse gatherings</a>.</p>
            @endforelse

            <h2 class="subheading">Gatherings you host</h2>
            @forelse ($hosting as $event)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                    <span>{{ $event->starts_at->format('D j M · g:i A') }} · {{ $event->registrations_count }} of {{ $event->capacity }} seats taken</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">You aren't hosting anything yet.</p>
            @endforelse

            <h2 class="subheading">Your communities</h2>
            @forelse ($communities as $community)
                @if ($loop->first)<ul class="row-list">@endif
                <li><a href="{{ route('communities.show', $community) }}">{{ $community->name }}</a></li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">You haven't joined a community yet. <a href="{{ route('communities.index') }}">Find one</a>.</p>
            @endforelse

            <h2 class="subheading">Your broadcasts</h2>
            @forelse ($broadcasts as $broadcast)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('broadcasts.show', $broadcast) }}">{{ $broadcast->title }}</a>
                    <span>{{ $broadcast->isLive() ? 'Live now' : ($broadcast->scheduled_at?->format('D j M · g:i A') ?? 'Unscheduled') }}</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">No scheduled or live broadcasts.</p>
            @endforelse

            <h2 class="subheading">Your convoys</h2>
            @forelse ($convoys as $convoy)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('convoys.show', $convoy) }}">{{ $convoy->name }}</a>
                    <span>{{ $convoy->departs_at->format('D j M · g:i A') }}</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">No upcoming convoys.</p>
            @endforelse

            <h2 class="subheading">Your routes</h2>
            @forelse ($routes as $route)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <a href="{{ route('routes.show', $route) }}">{{ $route->name }}</a>
                    <span>{{ number_format($route->distance_km, 1) }} km</span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">You haven't drawn a route yet.</p>
            @endforelse
        </section>
    </div>
@endsection
