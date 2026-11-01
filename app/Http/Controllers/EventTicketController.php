<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventTicketController extends Controller
{
    private function getEventData(string $slug): array
    {
        $events = [
            'invite-only-social-immerse' => [
                'title' => 'The Invite Only Social Immerse Event',
                'subtitle' => 'An intimate, invite-only gathering designed for connection and conversation.',
            ],
            '800-plus-journeys' => [
                'title' => 'The 800 Plus Journeys Event',
                'subtitle' => 'A multi-stop journey connecting cities through shared experiences.',
            ],
            'township-adventures' => [
                'title' => 'The Township Adventures Event',
                'subtitle' => 'Local adventures rooted in community and culture.',
            ],
            'badlands-bra-party-bus' => [
                'title' => 'The Badlands BRA Party Bus Event',
                'subtitle' => 'A rolling celebration through the Badlands.',
            ],
        ];

        return $events[$slug] ?? ['title' => 'Event', 'subtitle' => ''];
    }

    public function tickets(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.tickets', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function checkout(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.checkout', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function processCheckout(Request $request, string $slug): RedirectResponse
    {
        return redirect()->route('events.dynamic.thank-you', $slug)->with('status', 'Order placed successfully.');
    }

    public function thankYou(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.thank-you', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function confirmation(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.confirmation', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function download(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.download', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function pos(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.pos', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function venueDesigner(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.venue-designer', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function recurring(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.recurring', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function customFields(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.custom-fields', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function checkin(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.checkin', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }

    public function calendar(Request $request, string $slug): View
    {
        $event = $this->getEventData($slug);
        return view('events.calendar', [
            'event' => $event,
            'slug' => $slug,
        ]);
    }
}
