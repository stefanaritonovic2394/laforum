<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->signIn();
    }

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Updated',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Updated',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function unauthorized_users_cannot_update_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Updated',
            'body' => 'Updated body.'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Updated', $thread->fresh()->title);
            $this->assertEquals('Updated body.', $thread->fresh()->body);
        });
    }
}
