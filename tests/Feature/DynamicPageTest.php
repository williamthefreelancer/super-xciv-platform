<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_page_renders_markdown_and_strips_html(): void
    {
        $page = Page::factory()->create(['body' => "## Our rules\n\nBe kind.\n\n<script>alert(1)</script>"]);

        $this->get(route('pages.show', $page))
            ->assertOk()
            ->assertSee('<h2>Our rules</h2>', false)
            ->assertSee('Be kind.')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_draft_pages_are_hidden_from_everyone_but_admins(): void
    {
        $page = Page::factory()->draft()->create();

        $this->get(route('pages.show', $page))->assertNotFound();
        $this->actingAs(User::factory()->create())->get(route('pages.show', $page))->assertNotFound();
        $this->actingAs(User::factory()->admin()->create())->get(route('pages.show', $page))->assertOk();
    }

    public function test_footer_lists_published_footer_pages(): void
    {
        Page::factory()->create(['title' => 'Terms of Use', 'show_in_footer' => true]);
        Page::factory()->create(['title' => 'Hidden Policy', 'show_in_footer' => false]);
        Page::factory()->draft()->create(['title' => 'Draft Policy', 'show_in_footer' => true]);

        $this->get(route('home'))
            ->assertSee('Terms of Use')
            ->assertDontSee('Hidden Policy')
            ->assertDontSee('Draft Policy');
    }

    public function test_page_management_requires_an_administrator(): void
    {
        $this->get(route('admin.pages.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.pages.index'))->assertForbidden();
        $this->actingAs(User::factory()->admin()->create())->get(route('admin.pages.index'))->assertOk();
    }

    public function test_admin_can_create_update_and_delete_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.pages.store'), [
            'title' => 'Code of Conduct',
            'body' => 'Treat people well.',
            'is_published' => '1',
            'show_in_footer' => '1',
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::firstOrFail();
        $this->assertSame('code-of-conduct', $page->slug);
        $this->assertTrue($page->show_in_footer);

        $this->actingAs($admin)->put(route('admin.pages.update', $page), [
            'title' => 'Code of Conduct v2',
            'slug' => '',
            'body' => 'Updated.',
        ])->assertRedirect(route('admin.pages.index'));

        $page->refresh();
        $this->assertSame('Code of Conduct v2', $page->title);
        $this->assertSame('code-of-conduct', $page->slug);
        $this->assertFalse($page->is_published);

        $this->actingAs($admin)->delete(route('admin.pages.destroy', $page));
        $this->assertModelMissing($page);
    }

    public function test_non_admins_cannot_create_pages(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pages.store'), ['title' => 'X', 'body' => 'Y'])
            ->assertForbidden();

        $this->assertDatabaseCount('pages', 0);
    }

    public function test_page_slugs_must_be_unique(): void
    {
        Page::factory()->create(['slug' => 'terms']);

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.pages.store'), ['title' => 'Terms', 'slug' => 'terms', 'body' => 'Body'])
            ->assertSessionHasErrors('slug');
    }
}
