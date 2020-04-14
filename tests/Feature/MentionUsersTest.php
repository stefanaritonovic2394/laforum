<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $marko = create(User::class, ['name' => 'Marko']);

        $this->signIn($marko);

        $marija = create(User::class, ['name' => 'Marija']);

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => '@Marija look at this.'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $marija->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_given_characters()
    {
        create(User::class, ['name' => 'marko']);
        create(User::class, ['name' => 'marija']);

        $results = $this->json('GET', '/api/users', ['name' => 'marko']);

        $this->assertCount(1, $results->json());
    }
}
