@extends('layouts.app')

@section('title', config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<section class="home-hero" aria-labelledby="home-hero-title">
    <div class="home-hero-bg" aria-hidden="true"></div>
    <div class="page-wrap">
        <div class="home-hero-grid">
            <div class="home-hero-copy">
                <img src="{{ asset('images/the-super-ninety-four-app-white.svg') }}" alt="XCIV" class="home-hero-logo">
                <h1 id="home-hero-title" class="home-hero-title">Hospitality, in person.</h1>
                <p class="home-hero-subtitle">A community shaped by the people who bring people together.</p>
            </div>

            <div class="home-auth-panel" role="region" aria-label="Authentication and admission">
                <div class="home-auth-card">
                    <div class="home-auth-tabs" role="tablist" aria-label="Access options">
                        <button class="home-auth-tab is-active" id="tab-signin" role="tab" aria-selected="true" aria-controls="panel-signin" data-tab="signin">Sign in to SUPR XCIV</button>
                        <button class="home-auth-tab" id="tab-join" role="tab" aria-selected="false" aria-controls="panel-join" data-tab="join">Join by attendee admission</button>
                    </div>

                    <div class="home-auth-panels">
                        <section id="panel-signin" class="home-auth-panel-content is-active" role="tabpanel" aria-labelledby="tab-signin">
                            <p class="home-panel-lead">Sign in with your club and ID details.</p>
                            <form class="home-form" method="POST" action="{{ route('login') }}" novalidate>
                                @csrf
                                <div class="field">
                                    <label for="club">Select your club</label>
                                    <select id="club" name="club" required>
                                        <option value="" disabled selected>Select your club</option>
                                        <option value="general">General</option>
                                        <option value="hospitality">Hospitality</option>
                                        <option value="nightlife">Nightlife &amp; clubs</option>
                                        <option value="event_maker">Event maker</option>
                                    </select>
                                    <p class="field-hint">Choose the club you're affiliated with.</p>
                                </div>

                                <div class="field">
                                    <label for="participant_id">Participant ID</label>
                                    <input id="participant_id" name="participant_id" type="text" inputmode="text" autocomplete="off" placeholder="Enter participant ID" required>
                                </div>

                                <div class="field">
                                    <label for="member_id">Non-member or member ID</label>
                                    <input id="member_id" name="member_id" type="text" inputmode="text" autocomplete="off" placeholder="Enter member or non-member ID">
                                    <p class="field-hint">Leave blank if not applicable.</p>
                                </div>

                                <div class="field">
                                    <label for="full_name_signin">Full name</label>
                                    <input id="full_name_signin" name="full_name" type="text" autocomplete="name" placeholder="Enter your full name" required>
                                </div>

                                <div class="home-form-actions">
                                    <button type="submit" class="button button-block">Sign in</button>
                                </div>
                            </form>
                        </section>

                        <section id="panel-join" class="home-auth-panel-content" role="tabpanel" aria-labelledby="tab-join" id="join">
                            <p class="home-panel-lead">Apply for attendee admission. Complete step 1 below.</p>
                            <div class="home-steps" aria-label="Admission steps">
                                <div class="home-step is-active" data-step="1">
                                    <span class="home-step-number">1</span>
                                    <span class="home-step-label">Basic</span>
                                </div>
                                <div class="home-step" data-step="2">
                                    <span class="home-step-number">2</span>
                                    <span class="home-step-label">Profile</span>
                                </div>
                                <div class="home-step" data-step="3">
                                    <span class="home-step-number">3</span>
                                    <span class="home-step-label">Intent</span>
                                </div>
                            </div>

                            <form class="home-form" id="admission-form" method="POST" action="#" novalidate>
                                @csrf
                                <div class="home-step-panel is-active" data-step-panel="1">
                                    <div class="field">
                                        <label for="full_name_join">Your full name</label>
                                        <input id="full_name_join" name="full_name" type="text" autocomplete="name" placeholder="Enter your full name" required>
                                    </div>

                                    <div class="field">
                                        <label for="phone">Your phone number</label>
                                        <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="e.g., +1 555 123 4567" required>
                                    </div>

                                    <div class="field">
                                        <label for="email">Your email</label>
                                        <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                                    </div>

                                    <div class="field">
                                        <label for="age">Your age</label>
                                        <input id="age" name="age" type="number" inputmode="numeric" min="16" max="120" placeholder="Enter your age" required>
                                    </div>

                                    <div class="home-form-actions">
                                        <button type="button" class="button" data-next-step>Next step</button>
                                    </div>
                                </div>

                                <div class="home-step-panel" data-step-panel="2" aria-hidden="true">
                                    <div class="field">
                                        <label for="organization">Organization / Affiliation</label>
                                        <input id="organization" name="organization" type="text" placeholder="Where are you affiliated with?">
                                    </div>

                                    <div class="field">
                                        <label for="role">Role</label>
                                        <input id="role" name="role" type="text" placeholder="Your role (e.g., Host, Guest, Artist)">
                                    </div>

                                    <div class="field">
                                        <label for="city">City</label>
                                        <input id="city" name="city" type="text" placeholder="City">
                                    </div>

                                    <div class="home-form-actions home-form-actions-split">
                                        <button type="button" class="button button-quiet" data-prev-step>Back</button>
                                        <button type="button" class="button" data-next-step>Next step</button>
                                    </div>
                                </div>

                                <div class="home-step-panel" data-step-panel="3" aria-hidden="true">
                                    <div class="field">
                                        <label for="intent">Why do you want to attend?</label>
                                        <textarea id="intent" name="intent" rows="4" placeholder="Tell us a bit about your interest..."></textarea>
                                    </div>

                                    <div class="field checkbox-field">
                                        <label>
                                            <input type="checkbox" name="agree" required>
                                            <span>I agree to the terms and understand admission is subject to approval.</span>
                                        </label>
                                    </div>

                                    <div class="home-form-actions home-form-actions-split">
                                        <button type="button" class="button button-quiet" data-prev-step>Back</button>
                                        <button type="submit" class="button">Submit application</button>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    @vite(['resources/js/home.js'])
@endpush
