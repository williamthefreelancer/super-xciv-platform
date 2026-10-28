<?php

namespace Database\Seeders;

use App\Models\Broadcast;
use App\Models\Community;
use App\Models\CommunityPost;
use App\Models\Convoy;
use App\Models\Event;
use App\Models\Page;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $host = User::factory()->admin()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $events = Event::factory(5)->for($host, 'host')->create();

        $community = Community::factory()->for($host, 'owner')->create([
            'name' => 'Late Night Operators',
            'summary' => 'Club and bar operators swapping notes after close.',
        ]);
        $events->first()->update(['community_id' => $community->id]);
        CommunityPost::factory(3)->for($community)->for($host, 'author')->create();

        Broadcast::factory()->live()->for($host, 'host')->create(['title' => 'Opening Night Webcast']);
        Broadcast::factory()->audio()->for($host, 'host')->create(['title' => 'Industry Radio Hour']);

        $route = TravelRoute::factory()->for($host, 'owner')->create(['name' => 'London to Bath Run']);
        Convoy::factory()->for($host, 'host')->for($route, 'travelRoute')->create(['name' => 'Friday Convoy']);

        Page::create([
            'title' => 'Terms of Use',
            'slug' => 'terms',
            'body' => "## Terms of use\n\nBy using XCIV you agree to host and attend gatherings respectfully.",
            'show_in_footer' => true,
            'sort_order' => 1,
        ]);
        Page::create([
            'title' => 'Privacy',
            'slug' => 'privacy',
            'body' => "## Privacy\n\nAttendee lists are visible only to the host of a gathering.",
            'show_in_footer' => true,
            'sort_order' => 2,
        ]);
    }
}
