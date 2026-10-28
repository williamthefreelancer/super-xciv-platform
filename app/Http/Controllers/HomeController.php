<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'events' => Event::upcoming()->with('host')->withCount('registrations')->limit(3)->get(),
        ]);
    }
}
