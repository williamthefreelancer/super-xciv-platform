@props(['event'])

<article class="event-card">
    <p class="event-card-date">{{ $event->starts_at->format('D j M · g:i A') }}</p>
    <h3><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>
    <p class="event-card-summary">{{ $event->summary }}</p>
    <p class="event-card-meta">{{ $event->venue }}, {{ $event->city }}</p>
    <p class="event-card-foot">
        <span>Hosted by {{ $event->host->name }}</span>
        <span>{{ $event->isFull() ? 'Full' : $event->spotsRemaining().' seats left' }}</span>
    </p>
</article>
