<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guests_cannot_see_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());

        $this->get('/threads/'. $thread->id)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
