@extends('layouts.app')

@section('title', 'Manage pages | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section" aria-labelledby="pages-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Dashboard</p>
                    <h1 class="page-title" id="pages-title">Pages</h1>
                </div>
                <a class="button" href="{{ route('admin.pages.create') }}">New page</a>
            </div>

            @forelse ($pages as $page)
                @if ($loop->first)<ul class="row-list">@endif
                <li>
                    <span>
                        <a href="{{ route('pages.show', $page) }}">{{ $page->title }}</a>
                        <span>/p/{{ $page->slug }}</span>
                    </span>
                    <span class="row-actions">
                        <span>{{ $page->is_published ? 'Published' : 'Draft' }}{{ $page->show_in_footer ? ' · Footer' : '' }}</span>
                        <a href="{{ route('admin.pages.edit', $page) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">
                            @csrf
                            @method('DELETE')
                            <button class="link-button" type="submit">Delete</button>
                        </form>
                    </span>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <p class="form-note">No pages yet.</p>
            @endforelse
        </section>
    </div>
@endsection
