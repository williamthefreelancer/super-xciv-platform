<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $event['title'] ?? 'Event' }} — SUPR XCIV</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
      @font-face {
        font-family: 'Graphik';
        src: url('https://cdn.berghain.berlin/static/fonts/Graphik-Regular-Web.woff2') format('woff2');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: 'Graphik';
        src: url('https://cdn.berghain.berlin/static/fonts/Graphik-Semibold-Web.woff2') format('woff2');
        font-weight: 600;
        font-style: normal;
        font-display: swap;
      }
    </style>
    
    <link rel="stylesheet" href="{{ asset('css/event-berghain.css') }}">
    @stack('styles')
</head>

<body class="event-berghain">
    <div class="octagon"></div>
    <div class="event-container">
        <div class="event-layout">
            <header class="event-header">
                <div class="event-header-top">
                    <a class="logo" href="{{ route('home') }}">
                        <img src="{{ asset('images/the-super-ninety-four-app-white.svg') }}" alt="SUPR XCIV">
                    </a>
                    
                    <nav>
                        <ul class="main-menu">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('home') }}">Home</a></li>
                        </ul>
                    </nav>
                </div>
                
                <nav class="sidebar-info">
                    <div class="sidebar-section">
                        <h4>Location</h4>
                        <ul>
                            <li><a href="#">SUPR XCIV Venue<br/>United States<br/>USA</a></li>
                        </ul>
                    </div>
                    <div class="sidebar-section">
                        <h4>Info</h4>
                        <ul>
                            <li><a href="#">Events</a></li>
                        </ul>
                    </div>
                    <div class="sidebar-links">
                        <a href="#">Legal Notice</a>
                        <a href="#">Privacy & Terms</a>
                    </div>
                </nav>
            </header>
            
            <main class="event-main">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>

