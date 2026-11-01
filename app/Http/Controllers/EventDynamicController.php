<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class EventDynamicController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $events = [
            'invite-only-social-immerse' => [
                'title' => 'SUPR XCIV - Invite Only Social Immerse',
                'subtitle' => 'An intimate, invite-only gathering designed for connection and conversation.',
                'description' => 'Don\'t let the setting fool you: this curated social experience brings together community builders in an environment crafted for authentic connection. Expect immersive atmospheres, thoughtful programming, and meaningful introductions across hospitality, nightlife, and creative spaces.',
                'date' => 'Thursday 30.07.2026',
                'start_time' => '22:00',
                'lineup' => [
                    'Main Room' => [
                        ['time' => '22:00', 'name' => 'Zemög'],
                        ['time' => '00:30', 'name' => 'Occa B2B YSK'],
                    ],
                ],
                'tickets' => [
                    '$97.00 from SUPR XCIV',
                    '$97.00 at the door',
                ],
                'venue' => 'Private Venue',
                'city' => 'USA',
            ],
            '800-plus-journeys' => [
                'title' => 'SUPR XCIV - 800 Plus Journeys',
                'subtitle' => 'A multi-stop journey connecting cities through shared experiences.',
                'description' => 'The 800 Plus Journeys Event brings together travelers and hosts for a series of coordinated stops. Each leg offers its own character, with community checkpoints, local partners, and stories collected along the way. Join us for an adventure that spans hundreds of miles and countless connections.',
                'date' => 'Friday 07.08.2026',
                'start_time' => '18:00',
                'lineup' => [
                    'Departure' => [
                        ['time' => '18:00', 'name' => 'Route Opening'],
                        ['time' => '21:00', 'name' => 'Checkpoint One'],
                    ],
                ],
                'tickets' => [
                    '$97.00 from SUPR XCIV',
                    '$97.00 at the door',
                ],
                'venue' => 'Route-based',
                'city' => 'USA',
            ],
            'township-adventures' => [
                'title' => 'SUPR XCIV - Township Adventures',
                'subtitle' => 'Local adventures rooted in community and culture.',
                'description' => 'Explore neighborhoods through the lens of local hosts. Township Adventures highlight hidden gems, grassroots initiatives, and the people shaping their communities with warmth and authenticity. Discover the stories that make each township unique.',
                'date' => 'Saturday 15.08.2026',
                'start_time' => '14:00',
                'lineup' => [
                    'Tour' => [
                        ['time' => '14:00', 'name' => 'Community Walk'],
                        ['time' => '17:00', 'name' => 'Local Showcase'],
                    ],
                ],
                'tickets' => [
                    '$97.00 from SUPR XCIV',
                    '$97.00 at the door',
                ],
                'venue' => 'Township Locations',
                'city' => 'USA',
            ],
            'badlands-bra-party-bus' => [
                'title' => 'SUPR XCIV - Badlands BRA Party Bus',
                'subtitle' => 'A rolling celebration through the Badlands.',
                'description' => 'Climb aboard for a high-energy party bus experience through the Badlands. With curated playlists, themed stops, and a vibrant crowd, it\'s a celebration on wheels built for the bold. Ride, connect, and celebrate as we cruise through stunning landscapes.',
                'date' => 'Saturday 22.08.2026',
                'start_time' => '23:00',
                'lineup' => [
                    'Party Bus' => [
                        ['time' => '23:00', 'name' => 'DJ Onboard'],
                        ['time' => '02:00', 'name' => 'Late Set'],
                    ],
                ],
                'tickets' => [
                    '$97.00 from SUPR XCIV',
                    '$97.00 at the door',
                ],
                'venue' => 'Party Bus',
                'city' => 'USA',
            ],
        ];

        $event = $events[$slug] ?? [
            'title' => 'SUPR XCIV - Event',
            'subtitle' => 'Event details coming soon.',
            'description' => 'This event page is being prepared. Check back for updates.',
            'date' => 'TBA',
            'tickets' => [
                '$97.00 from SUPR XCIV',
                '$97.00 at the door',
            ],
        ];

        return view('events.supr-xciv', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }
}
