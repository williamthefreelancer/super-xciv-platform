<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function registrationData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Sam Host',
            'email' => 'sam@example.com',
            'organization_name' => 'The Annex',
            'sector' => 'hospitality',
            'city' => 'Austin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_registration_and_login_pages_render(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Join XCIV');
        $this->get(route('login'))->assertOk()->assertSee('Log in');
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register'), $this->registrationData())
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'sam@example.com', 'sector' => 'hospitality']);
    }

    public function test_registration_validates_input(): void
    {
        User::factory()->create(['email' => 'sam@example.com']);

        $this->post(route('register'), $this->registrationData([
            'sector' => 'unknown',
            'password_confirmation' => 'different',
        ]))->assertSessionHasErrors(['email', 'sector', 'password']);

        $this->assertGuest();
    }

    public function test_user_can_log_in_and_out(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['name' => 'Dash Tester']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dash Tester');
    }
}
