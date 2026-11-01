@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="page-wrap">
    <section class="page-section">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
            <div>
                <h1 style="font-family: 'Lato', sans-serif; font-weight: 700; color: #ffffff; line-height: 1.2;">This is a heading title<br>with at least 2 lines of text</h1>
                <a href="#" class="button" style="background: #000000; color: #ffffff; border-color: #000000;">Call to action</a>
            </div>
            <div>
                <h2 style="font-family: 'Lato', sans-serif; font-weight: 700; color: #ffffff; margin-bottom: 1.5rem;">Sign in to SUPR XCIV</h2>
                <form style="display: grid; gap: 1rem;">
                    <div class="field">
                        <label style="color: #ffffff;">Select your club</label>
                        <select>
                            <option value="">Select your club</option>
                        </select>
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Participant ID</label>
                        <input type="text" placeholder="Participant ID">
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Non-member or member ID</label>
                        <input type="text" placeholder="Non-member or member ID">
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Full name</label>
                        <input type="text" placeholder="Full name">
                    </div>
                </form>
                
                <h2 style="font-family: 'Lato', sans-serif; font-weight: 700; color: #ffffff; margin-top: 2rem; margin-bottom: 0.5rem;">Join by attendee admission</h2>
                <p style="color: #ffffff; margin-bottom: 1rem;">Event admission by multi-step form for admission intelligence and by approval.</p>
                <p style="color: #ffffff; font-size: 0.9rem; margin-bottom: 1rem;">Step 1: Basic | Step 2: Profile | Step 3: Intent</p>
                <form style="display: grid; gap: 1rem;">
                    <div class="field">
                        <label style="color: #ffffff;">Your full name</label>
                        <input type="text" placeholder="Your full name">
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Your phone number</label>
                        <input type="text" placeholder="Your phone number">
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Your email</label>
                        <input type="email" placeholder="Your email">
                    </div>
                    <div class="field">
                        <label style="color: #ffffff;">Your AGE</label>
                        <input type="number" placeholder="Your AGE">
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
