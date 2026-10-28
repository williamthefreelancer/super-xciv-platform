<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_pages_render(): void
    {
        $this->get(route('operators'))->assertOk()->assertSee('Event makers');
        $this->get(route('about'))->assertOk()->assertSee('Hospitality');
    }

    public function test_home_page_shows_upcoming_events(): void
    {
        $event = Event::factory()->create(['title' => 'Harbour Night']);

        $this->get(route('home'))->assertOk()->assertSee('Harbour Night');
    }
}
