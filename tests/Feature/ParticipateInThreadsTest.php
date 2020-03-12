<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_cannot_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/1/replies', []);
    }

    /** @test */
    public function an_authenticated_user_can_participate_in_threads()
    {
        $user = create(User::class);
        $this->be($user);

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post('/threads/'. $thread->id .'/replies', $reply->toArray());

        $this->get('/threads/'. $thread->id)
            ->assertSee($reply->body);
    }
}
