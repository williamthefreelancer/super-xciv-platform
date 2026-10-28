@extends('layouts.app')

@section('title', $broadcast->title . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section event-detail" data-broadcast data-status="{{ $broadcast->status }}" data-status-url="{{ route('broadcasts.status', $broadcast) }}">
            <div class="event-detail-main">
                <p class="eyebrow">
                    <span class="eyebrow-line" aria-hidden="true"></span>
                    {{ \App\Models\Broadcast::TYPES[$broadcast->type] ?? 'Broadcast' }}
                    @if ($broadcast->isLive())<span class="badge badge-live">Live</span>@endif
                </p>
                <h1 class="page-title">{{ $broadcast->title }}</h1>
                @if ($broadcast->description)
                    <p class="intro-description">{{ $broadcast->description }}</p>
                @endif

                @error('broadcast')
                    <p class="flash flash-error" role="alert">{{ $message }}</p>
                @enderror

                @if ($broadcast->isLive() && $playback)
                    <div class="player {{ $broadcast->type === 'audio' ? 'player-audio' : '' }}">
                        @switch($playback['kind'])
                            @case('embed')
                                <iframe src="{{ $playback['src'] }}" title="{{ $broadcast->title }}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                @break
                            @case('hls')
                                @if ($broadcast->type === 'audio')
                                    <audio controls autoplay data-hls="{{ $playback['src'] }}"></audio>
                                @else
                                    <video controls autoplay playsinline data-hls="{{ $playback['src'] }}"></video>
                                @endif
                                @break
                            @case('audio')
                                <audio controls autoplay src="{{ $playback['src'] }}"></audio>
                                @break
                            @default
                                <video controls autoplay playsinline src="{{ $playback['src'] }}"></video>
                        @endswitch
                    </div>
                @elseif ($broadcast->isLive())
                    <div id="livekit-stage"
                         class="livekit-stage {{ $broadcast->type === 'audio' ? 'livekit-stage-audio' : '' }}"
                         data-livekit
                         data-token-url="{{ route('broadcasts.token', $broadcast) }}"
                         data-broadcast-type="{{ $broadcast->type }}"
                         data-is-host="{{ $canManage ? 'true' : 'false' }}"
                         data-is-live="true">
                        <div class="livekit-media-grid" id="livekit-media-grid">
                            <div class="livekit-waiting" id="livekit-placeholder">
                                <p>Connecting to LiveKit room...</p>
                            </div>
                        </div>

                        @if ($canManage)
                            <div class="livekit-studio-bar" id="livekit-host-controls">
                                <button type="button" class="button button-small" id="lk-join-btn">Connect Studio</button>
                                <button type="button" class="button button-small button-quiet" id="lk-mic-btn" disabled>Unmute Mic</button>
                                @if ($broadcast->type === 'video')
                                    <button type="button" class="button button-small button-quiet" id="lk-cam-btn" disabled>Turn Camera On</button>
                                    <button type="button" class="button button-small button-quiet" id="lk-screen-btn" disabled>Share Screen</button>
                                @endif
                                <button type="button" class="button button-small button-quiet" id="lk-leave-btn" style="display:none;">Disconnect</button>
                            </div>
                        @endif

                        <div class="livekit-status-bar">
                            <span id="lk-status-indicator" class="status-indicator">Ready</span>
                            <span id="lk-participants-count"></span>
                        </div>
                    </div>
                @elseif ($broadcast->isEnded())
                    <div class="player-placeholder">
                        <p>This broadcast ended {{ $broadcast->ended_at?->diffForHumans() }}.</p>
                    </div>
                @else
                    <div class="player-placeholder">
                        <p>
                            {{ $broadcast->scheduled_at ? 'Starts '.$broadcast->scheduled_at->format('l j F · g:i A') : 'Start time to be announced.' }}
                            This page updates automatically when the host goes live.
                        </p>
                    </div>
                    @if ($canManage)
                        <div id="livekit-stage"
                             class="livekit-stage"
                             style="margin-top: 16px;"
                             data-livekit
                             data-token-url="{{ route('broadcasts.token', $broadcast) }}"
                             data-broadcast-type="{{ $broadcast->type }}"
                             data-is-host="true"
                             data-is-live="false">
                            <div class="livekit-studio-bar" id="livekit-host-controls">
                                <span class="form-note" style="margin-right: 12px;">Pre-broadcast studio test:</span>
                                <button type="button" class="button button-small button-quiet" id="lk-join-btn">Test Camera &amp; Mic</button>
                                <button type="button" class="button button-small button-quiet" id="lk-mic-btn" disabled>Mic</button>
                                @if ($broadcast->type === 'video')
                                    <button type="button" class="button button-small button-quiet" id="lk-cam-btn" disabled>Camera</button>
                                    <button type="button" class="button button-small button-quiet" id="lk-screen-btn" disabled>Screen</button>
                                @endif
                                <button type="button" class="button button-small button-quiet" id="lk-leave-btn" style="display:none;">Leave</button>
                            </div>
                            <div class="livekit-media-grid" id="livekit-media-grid" style="display:none;"></div>
                            <div class="livekit-status-bar">
                                <span id="lk-status-indicator" class="status-indicator">Testing studio</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <aside class="event-detail-side" aria-label="Broadcast details">
                <dl class="detail-list">
                    <div><dt>Host</dt><dd>{{ $broadcast->host->name }}</dd></div>
                    <div><dt>Status</dt><dd>{{ ucfirst($broadcast->status) }}</dd></div>
                    <div><dt>Engine</dt><dd>{{ $playback ? 'External Stream' : 'LiveKit WebRTC' }}</dd></div>
                    @if ($broadcast->event)
                        <div><dt>Gathering</dt><dd><a href="{{ route('events.show', $broadcast->event) }}">{{ $broadcast->event->title }}</a></dd></div>
                    @endif
                </dl>

                @if ($canManage && ! $broadcast->isEnded())
                    <h2 class="subheading">Host controls</h2>
                    <form class="form" method="POST" action="{{ route('broadcasts.live', $broadcast) }}">
                        @csrf
                        <div class="field">
                            <label for="stream_url">Stream URL <span class="optional">(optional — leave empty to broadcast with LiveKit)</span></label>
                            <input id="stream_url" name="stream_url" type="url" value="{{ old('stream_url', $broadcast->stream_url) }}" placeholder="Leave blank for LiveKit WebRTC">
                            @error('stream_url')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        @if (! $broadcast->isLive())
                            <button class="button button-block" type="submit">Go live</button>
                        @else
                            <button class="button button-block button-quiet" type="submit">Update stream</button>
                        @endif
                    </form>

                    @if ($broadcast->isLive())
                        <form method="POST" action="{{ route('broadcasts.end', $broadcast) }}">
                            @csrf
                            <button class="button button-block" type="submit">End broadcast</button>
                        </form>
                    @endif
                @endif
            </aside>
        </article>
    </div>
@endsection
