<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\UserFollowedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserFollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Notification::fake();

        $response = $this
            ->actingAs($userA)
            ->post(route('users.follow', $userB->id));

        $response->assertRedirect();
        $this->assertTrue($userA->isFollowing($userB));
        $this->assertTrue($userB->followers->contains($userA));

        Notification::assertSentTo(
            $userB,
            UserFollowedNotification::class,
            function ($notification, $channels) use ($userA) {
                return in_array('database', $channels);
            }
        );
    }

    public function test_user_can_unfollow_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Start by following B
        $userA->following()->attach($userB->id);
        $this->assertTrue($userA->isFollowing($userB));

        $response = $this
            ->actingAs($userA)
            ->post(route('users.follow', $userB->id));

        $response->assertRedirect();
        $this->assertFalse($userA->isFollowing($userB));
    }

    public function test_user_cannot_follow_themselves(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('users.follow', $user->id));

        $response->assertSessionHas('error', 'You cannot follow yourself.');
        $this->assertFalse($user->isFollowing($user));
    }

    public function test_followers_and_following_tabs_display_on_profile(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // userA follows userB
        $userA->following()->attach($userB->id);

        // Check userB's followers tab
        $response = $this->get(route('users.show', ['username' => $userB->username, 'tab' => 'followers']));
        $response->assertOk();
        $response->assertSee($userA->name);

        // Check userA's following tab
        $response = $this->get(route('users.show', ['username' => $userA->username, 'tab' => 'following']));
        $response->assertOk();
        $response->assertSee($userB->name);
    }
}
