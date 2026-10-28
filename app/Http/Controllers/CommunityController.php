<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommunityRequest;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $communities = Community::query()
            ->withCount('members')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('communities.index', ['communities' => $communities, 'search' => $search]);
    }

    public function show(Request $request, Community $community): View
    {
        $community->load('owner')->loadCount('members');

        $isMember = $request->user() !== null
            && $community->members()->where('users.id', $request->user()->id)->exists();

        return view('communities.show', [
            'community' => $community,
            'isMember' => $isMember,
            'posts' => $community->posts()->with('author')->latest()->paginate(10),
            'events' => $community->events()->upcoming()->with('host')->withCount('registrations')->limit(6)->get(),
            'members' => $isMember ? $community->members()->orderBy('name')->limit(24)->get() : collect(),
        ]);
    }

    public function create(): View
    {
        return view('communities.create');
    }

    public function store(StoreCommunityRequest $request): RedirectResponse
    {
        $community = $request->user()->ownedCommunities()->create($request->validated());
        $community->members()->attach($request->user()->id);

        return redirect()->route('communities.show', $community)->with('status', 'Your community is live.');
    }
}
