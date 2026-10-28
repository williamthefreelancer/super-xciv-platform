@props(['href', 'title', 'eyebrow' => null, 'summary' => null, 'meta' => null, 'left' => null, 'right' => null, 'live' => false])

<article class="event-card">
    @if ($eyebrow || $live)
        <p class="event-card-date">
            {{ $eyebrow }}
            @if ($live)<span class="badge badge-live">Live</span>@endif
        </p>
    @endif
    <h3><a href="{{ $href }}">{{ $title }}</a></h3>
    @if ($summary)<p class="event-card-summary">{{ $summary }}</p>@endif
    @if ($meta)<p class="event-card-meta">{{ $meta }}</p>@endif
    @if ($left || $right)
        <p class="event-card-foot"><span>{{ $left }}</span><span>{{ $right }}</span></p>
    @endif
</article>
