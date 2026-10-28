@extends('layouts.app')

@section('title', ($page->exists ? 'Edit page' : 'New page') . ' | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page" aria-labelledby="page-form-title">
            <p class="eyebrow">Dashboard</p>
            <h1 class="page-title" id="page-form-title">{{ $page->exists ? 'Edit page' : 'New page' }}</h1>

            <form class="form" method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
                @csrf
                @if ($page->exists)
                    @method('PUT')
                @endif

                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $page->title) }}" maxlength="150" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="slug">Address <span class="optional">(leave blank to generate from the title)</span></label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $page->slug) }}" maxlength="120">
                    @error('slug')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="body">Content <span class="optional">(Markdown)</span></label>
                    <textarea id="body" name="body" rows="14" required>{{ old('body', $page->body) }}</textarea>
                    @error('body')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="sort_order">Footer order</label>
                        <input id="sort_order" name="sort_order" type="number" min="0" max="65535" value="{{ old('sort_order', $page->sort_order) }}">
                        @error('sort_order')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label class="checkbox"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published))> Published</label>
                        <label class="checkbox"><input type="checkbox" name="show_in_footer" value="1" @checked(old('show_in_footer', $page->show_in_footer))> Show in footer</label>
                    </div>
                </div>

                <button class="button" type="submit">{{ $page->exists ? 'Save changes' : 'Create page' }}</button>
            </form>
        </section>
    </div>
@endsection
