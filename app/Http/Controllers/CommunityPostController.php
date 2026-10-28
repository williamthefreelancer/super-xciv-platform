<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommunityPostRequest;
use App\Models\Community;
use App\Models\CommunityPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommunityPostController extends Controller
{
    public function store(StoreCommunityPostRequest $request, Community $community): RedirectResponse
    {
        abort_unless($community->members()->where('users.id', $request->user()->id)->exists(), 403);

        $community->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        return back()->with('status', 'Posted.');
    }

    public function destroy(Request $request, Community $community, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->community_id === $community->id, 404);
        abort_unless(
            in_array($request->user()->id, [$post->user_id, $community->owner_id], true),
            403,
        );

        $post->delete();

        return back()->with('status', 'Post removed.');
    }
}
