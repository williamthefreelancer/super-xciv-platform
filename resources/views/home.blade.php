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
            <div></div>
        </div>
    </section>
</div>
@endsection
