@extends('layouts.app')

@section('title', $page->title . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <article class="page-section form-narrow">
            @unless ($page->is_published)
                <p class="flash flash-error" role="status">Draft: only administrators can see this page.</p>
            @endunless
            <h1 class="page-title">{{ $page->title }}</h1>
            <div class="prose markdown">{{ $page->renderedBody() }}</div>
        </article>
    </div>
@endsection
