<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
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
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect(route('login'));

        $this->post(route('threads.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_before_creating_threads()
    {
        $user = factory(User::class)->state('unconfirmed')->create();

        $this->signIn($user);

        $thread = make(Thread::class);

        $this->post(route('threads.index'), $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'You first need to confirm your email address');
    }

    /** @test */
    public function a_user_can_create_new_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $response = $this->post(route('threads.index'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create(Thread::class, ['title' => 'Foo Title', 'slug' => 'foo-title']);

        $this->assertEquals('foo-title', $thread->fresh()->slug);

        $this->post(route('threads.index'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads.index'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function unauthorized_users_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())->assertRedirect(route('login'));

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.index'), $thread->toArray());
    }
}
