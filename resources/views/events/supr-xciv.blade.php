@extends('layouts.event-berghain')

@section('content')
    <figure class="event-hero-figure">
        <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1600&h=600&fit=crop" alt="{{ $event['title'] ?? 'Event' }}" class="event-hero-image">
        <figcaption class="event-hero-caption">
            <a href="#">Artwork by: SUPR XCIV</a>
        </figcaption>
    </figure>

    <div class="event-content">
        <p class="event-date">
            @if(isset($event['date']) && $event['date'] !== 'TBA')
                {{ $event['date'] }}
            @else
                Thursday <span class="date">TBA</span>
            @endif
            @if(isset($event['start_time']))
                start {{ $event['start_time'] }}
            @endif
        </p>

        <h1 class="event-title">{{ $event['title'] ?? 'Event' }}</h1>

        @if(isset($event['lineup']) && !empty($event['lineup']))
            @foreach($event['lineup'] as $stageName => $artists)
                <div class="event-lineup">
                    <div class="event-lineup-stage">
                        <h2 class="event-lineup-stage-title">{{ $stageName }}</h2>
                        <ul class="event-lineup-list">
                            @foreach($artists as $artist)
                                <li class="event-lineup-item">
                                    @if(isset($artist['time']))
                                        <span class="time">{{ $artist['time'] }}</span>
                                    @endif
                                    <span class="artist">{{ $artist['name'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="event-tickets">
            <h2>Tickets</h2>
            @if(isset($event['tickets']))
                @foreach($event['tickets'] as $ticket)
                    <p class="ticket-info">{{ $ticket }}</p>
                @endforeach
            @else
                <p class="ticket-info">$97.00 from <a href="#">SUPR XCIV</a></p>
                <p class="ticket-info">$97.00 at the door</p>
            @endif
        </div>

        <div class="event-description">
            @if(isset($event['description']))
                <p>{{ $event['description'] }}</p>
            @endif
            @if(isset($event['subtitle']))
                <p>{{ $event['subtitle'] }}</p>
            @endif
        </div>
    </div>
@endsection
