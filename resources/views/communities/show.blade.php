@extends('layouts.app')

@section('title', $community->name . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section event-detail">
            <div class="event-detail-main">
                <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Community{{ $community->city ? ' · '.$community->city : '' }}</p>
                <h1 class="page-title">{{ $community->name }}</h1>
                <p class="intro-description">{{ $community->summary }}</p>

                @if ($community->description)
                    <div class="prose">
                        @foreach (preg_split('/\R{2,}/', $community->description) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                @endif

                @if ($events->isNotEmpty())
                    <h2 class="subheading">Upcoming gatherings</h2>
                    <div class="event-grid">
                        @foreach ($events as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @endif

                <h2 class="subheading">Community board</h2>

                @if ($isMember)
                    <form class="form" method="POST" action="{{ route('communities.posts.store', $community) }}">
                        @csrf
                        <div class="field">
                            <label for="body" class="visually-hidden">Share an update</label>
                            <textarea id="body" name="body" rows="3" maxlength="2000" placeholder="Share an update with the community" required>{{ old('body') }}</textarea>
                            @error('body')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <button class="button button-small" type="submit">Post</button>
                    </form>
                @endif

                @forelse ($posts as $post)
                    @if ($loop->first)<div class="post-list">@endif
                    <article class="post">
                        <p class="post-meta">
                            <strong>{{ $post->author->name }}</strong>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="post-body">{{ $post->body }}</p>
                        @if (auth()->id() === $post->user_id || auth()->id() === $community->owner_id)
                            <form method="POST" action="{{ route('communities.posts.destroy', [$community, $post]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="link-button" type="submit">Remove</button>
                            </form>
                        @endif
                    </article>
                    @if ($loop->last)</div>@endif
                @empty
                    <p class="form-note">Nothing posted yet.</p>
                @endforelse

                {{ $posts->links() }}
            </div>

            <aside class="event-detail-side" aria-label="Community details">
                <dl class="detail-list">
                    <div><dt>Founded by</dt><dd>{{ $community->owner->name }}</dd></div>
                    <div><dt>Members</dt><dd>{{ $community->members_count }}</dd></div>
                </dl>

                @error('membership')
                    <p class="flash flash-error" role="alert">{{ $message }}</p>
                @enderror

                @guest
                    <a class="button button-block" href="{{ route('login') }}">Log in to join</a>
                @elseif ($isMember)
                    @if (auth()->id() !== $community->owner_id)
                        <form method="POST" action="{{ route('communities.leave', $community) }}">
                            @csrf
                            @method('DELETE')
                            <button class="button button-block button-quiet" type="submit">Leave community</button>
                        </form>
                    @else
                        <p class="form-note">You run this community.</p>
                    @endif
                @else
                    <form method="POST" action="{{ route('communities.join', $community) }}">
                        @csrf
                        <button class="button button-block" type="submit">Join community</button>
                    </form>
                @endguest

                @if ($members->isNotEmpty())
                    <h2 class="subheading">Members</h2>
                    <ul class="plain-list">
                        @foreach ($members as $member)
                            <li>{{ $member->name }}@if ($member->organization_name) <span>{{ $member->organization_name }}</span>@endif</li>
                        @endforeach
                    </ul>
                @endif
            </aside>
        </article>
    </div>
@endsection
