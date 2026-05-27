<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/settings');

        $response->assertOk();
    }

    public function test_settings_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put('/settings', [
                'name' => 'Test User',
                'bio' => 'This is my new bio',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.show', $user->username));

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('This is my new bio', $user->bio);
    }
}
