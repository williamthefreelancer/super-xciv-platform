@extends('layouts.app')

@section('title', 'Communities | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="communities-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Find your people</p>
                    <h1 class="page-title" id="communities-title">Communities</h1>
                </div>
                @auth
                    <a class="button" href="{{ route('communities.create') }}">Start a community</a>
                @endauth
            </div>

            <form class="filters" method="GET" action="{{ route('communities.index') }}">
                <input type="search" name="q" value="{{ $search }}" placeholder="Search communities" aria-label="Search communities">
                <button class="button button-small" type="submit">Search</button>
            </form>

            @if ($communities->isEmpty())
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">&#10022;</span>
                    <div>
                        <h3>No communities found.</h3>
                        <p>Start the first one and invite your peers.</p>
                    </div>
                    <span class="empty-state-note">OPEN TABLE</span>
                </div>
            @else
                <div class="event-grid">
                    @foreach ($communities as $community)
                        <x-info-card
                            :href="route('communities.show', $community)"
                            :title="$community->name"
                            :eyebrow="$community->city"
                            :summary="$community->summary"
                            :left="$community->members_count.' '.\Illuminate\Support\Str::plural('member', $community->members_count)"
                        />
                    @endforeach
                </div>

                {{ $communities->links() }}
            @endif
        </section>
    </div>
@endsection
