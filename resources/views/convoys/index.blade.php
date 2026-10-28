@extends('layouts.app')

@section('title', 'Convoys | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="convoys-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Arrive together</p>
                    <h1 class="page-title" id="convoys-title">Convoys</h1>
                </div>
                @auth
                    <a class="button" href="{{ route('convoys.create') }}">Start a convoy</a>
                @endauth
            </div>

            @if ($convoys->isEmpty())
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">&#10022;</span>
                    <div>
                        <h3>No convoys departing soon.</h3>
                        <p>Pick a route and start one for your next gathering.</p>
                    </div>
                    <span class="empty-state-note">OPEN ROAD</span>
                </div>
            @else
                <div class="event-grid">
                    @foreach ($convoys as $convoy)
                        <x-info-card
                            :href="route('convoys.show', $convoy)"
                            :title="$convoy->name"
                            :eyebrow="$convoy->departs_at->format('D j M · g:i A')"
                            :summary="$convoy->notes"
                            :meta="$convoy->travelRoute->name.' · '.number_format($convoy->travelRoute->distance_km, 1).' km'"
                            :left="'Led by '.$convoy->host->name"
                            :right="$convoy->isFull() ? 'Full' : $convoy->spotsRemaining().' seats left'"
                        />
                    @endforeach
                </div>

                {{ $convoys->links() }}
            @endif
        </section>
    </div>
@endsection
