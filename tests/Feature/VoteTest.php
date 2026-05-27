<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $author;
    protected Question $question;
    protected Answer $answer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['reputation' => 100]);
        $this->author = User::factory()->create(['reputation' => 100]);

        $this->question = Question::create([
            'user_id' => $this->author->id,
            'title' => 'Sample Title Question',
            'slug' => 'sample-title-question',
            'body' => 'Sample body content for question',
            'votes_count' => 0,
        ]);

        $this->answer = Answer::create([
            'user_id' => $this->author->id,
            'question_id' => $this->question->id,
            'body' => 'Sample answer content text',
            'votes_count' => 0,
        ]);
    }

    public function test_user_can_upvote_an_answer()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), [
            'value' => 1,
        ]);

        $response->assertRedirect();
        $this->assertEquals(110, $this->author->fresh()->reputation); // +10
        $this->assertEquals(1, $this->answer->fresh()->votes_count);
    }

    public function test_user_can_downvote_an_answer()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), [
            'value' => -1,
        ]);

        $response->assertRedirect();
        $this->assertEquals(98, $this->author->fresh()->reputation); // -2
        $this->assertEquals(-1, $this->answer->fresh()->votes_count);
    }

    public function test_user_can_neutralize_an_upvote()
    {
        $this->actingAs($this->user);

        // Upvote first
        $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => 1]);
        $this->assertEquals(110, $this->author->fresh()->reputation);

        // Neutralize by upvoting again
        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => 1]);

        $response->assertRedirect();
        $this->assertEquals(100, $this->author->fresh()->reputation); // back to 100
        $this->assertEquals(0, $this->answer->fresh()->votes_count);
    }

    public function test_user_can_neutralize_a_downvote()
    {
        $this->actingAs($this->user);

        // Downvote first
        $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => -1]);
        $this->assertEquals(98, $this->author->fresh()->reputation);

        // Neutralize by downvoting again
        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => -1]);

        $response->assertRedirect();
        $this->assertEquals(100, $this->author->fresh()->reputation); // back to 100 (+2 points recovered)
        $this->assertEquals(0, $this->answer->fresh()->votes_count);
    }

    public function test_user_can_change_vote_from_upvote_to_downvote()
    {
        $this->actingAs($this->user);

        // Upvote first
        $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => 1]);
        $this->assertEquals(110, $this->author->fresh()->reputation);

        // Change to downvote
        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => -1]);

        $response->assertRedirect();
        $this->assertEquals(98, $this->author->fresh()->reputation); // 110 - 12 = 98
        $this->assertEquals(-1, $this->answer->fresh()->votes_count);
    }

    public function test_user_can_change_vote_from_downvote_to_upvote()
    {
        $this->actingAs($this->user);

        // Downvote first
        $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => -1]);
        $this->assertEquals(98, $this->author->fresh()->reputation);

        // Change to upvote
        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), ['value' => 1]);

        $response->assertRedirect();
        $this->assertEquals(110, $this->author->fresh()->reputation); // 98 + 12 = 110
        $this->assertEquals(1, $this->answer->fresh()->votes_count);
    }

    public function test_user_cannot_vote_on_their_own_answer()
    {
        $this->actingAs($this->author);

        $response = $this->post(route('votes', ['type' => 'answer', 'id' => $this->answer->id]), [
            'value' => 1,
        ]);

        $response->assertSessionHas('error', 'You cannot vote on your own post.');
        $this->assertEquals(100, $this->author->fresh()->reputation);
    }
}
