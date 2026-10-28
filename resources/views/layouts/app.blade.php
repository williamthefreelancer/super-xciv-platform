<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f6f1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ config('app.name') }} home">
            <span class="brand-mark" aria-hidden="true">X</span>
            <span class="brand-name">XCIV</span>
            <span class="brand-divider" aria-hidden="true"></span>
            <span class="brand-caption">HOSPITALITY<br>IN PERSON</span>
        </a>
        <nav class="primary-nav" aria-label="Main navigation">
            <a class="nav-link {{ request()->routeIs('home', 'events.*') ? 'nav-link-active' : '' }}" href="{{ route('events.index') }}">Gatherings</a>
            <a class="nav-link {{ request()->routeIs('communities.*') ? 'nav-link-active' : '' }}" href="{{ route('communities.index') }}">Communities</a>
            <a class="nav-link {{ request()->routeIs('broadcasts.*') ? 'nav-link-active' : '' }}" href="{{ route('broadcasts.index') }}">Broadcasts</a>
            <a class="nav-link {{ request()->routeIs('routes.*', 'convoys.*') ? 'nav-link-active' : '' }}" href="{{ route('convoys.index') }}">Convoys</a>
            <a class="nav-link {{ request()->routeIs('operators') ? 'nav-link-active' : '' }}" href="{{ route('operators') }}">For operators</a>
            <a class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}" href="{{ route('about') }}">About</a>
        </nav>
        <div class="header-actions">
            @auth
                <a class="header-link" href="{{ route('dashboard') }}">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button-small button-quiet" type="submit">Log out</button>
                </form>
            @else
                <a class="header-link" href="{{ route('login') }}">Log in</a>
                <a class="button button-small" href="{{ route('register') }}">Join XCIV</a>
            @endauth
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="page-wrap">
                <p class="flash flash-success" role="status">{{ session('status') }}</p>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer" id="about">
        <a class="footer-brand" href="{{ route('home') }}">XCIV <span>Hospitality, in person.</span></a>
        <p>A community shaped by the people who bring people together.</p>
        @if ($footerPages->isNotEmpty())
            <nav class="footer-links" aria-label="Footer">
                @foreach ($footerPages as $footerPage)
                    <a href="{{ route('pages.show', $footerPage) }}">{{ $footerPage->title }}</a>
                @endforeach
            </nav>
        @else
            <span class="footer-location">Made for real rooms, everywhere.</span>
        @endif
    </footer>
</body>
</html>