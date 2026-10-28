@extends('layouts.app')

@section('title', 'Broadcasts | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="broadcasts-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Live from the room</p>
                    <h1 class="page-title" id="broadcasts-title">Broadcasts</h1>
                </div>
                @auth
                    <a class="button" href="{{ route('broadcasts.create') }}">Schedule a broadcast</a>
                @endauth
            </div>

            <h2 class="subheading">Live now</h2>
            @forelse ($live as $broadcast)
                @if ($loop->first)<div class="event-grid">@endif
                <x-info-card
                    :href="route('broadcasts.show', $broadcast)"
                    :title="$broadcast->title"
                    :eyebrow="\App\Models\Broadcast::TYPES[$broadcast->type] ?? ''"
                    :summary="$broadcast->description"
                    :left="'Hosted by '.$broadcast->host->name"
                    :right="'Since '.$broadcast->started_at?->format('g:i A')"
                    :live="true"
                />
                @if ($loop->last)</div>@endif
            @empty
                <p class="form-note">Nothing is live right now.</p>
            @endforelse

            <h2 class="subheading">Coming up</h2>
            @forelse ($scheduled as $broadcast)
                @if ($loop->first)<div class="event-grid">@endif
                <x-info-card
                    :href="route('broadcasts.show', $broadcast)"
                    :title="$broadcast->title"
                    :eyebrow="$broadcast->scheduled_at?->format('D j M · g:i A') ?? 'Time to be announced'"
                    :summary="$broadcast->description"
                    :left="'Hosted by '.$broadcast->host->name"
                    :right="\App\Models\Broadcast::TYPES[$broadcast->type] ?? ''"
                />
                @if ($loop->last)</div>@endif
            @empty
                <p class="form-note">No broadcasts scheduled.</p>
            @endforelse

            @if ($ended->isNotEmpty())
                <h2 class="subheading">Recently ended</h2>
                <ul class="row-list">
                    @foreach ($ended as $broadcast)
                        <li>
                            <a href="{{ route('broadcasts.show', $broadcast) }}">{{ $broadcast->title }}</a>
                            <span>{{ $broadcast->ended_at?->format('D j M') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
