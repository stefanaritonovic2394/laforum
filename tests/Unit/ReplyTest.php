<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users()
    {
        $reply = new Reply([
            'body' => '@Marija wants to talk to @Marko'
        ]);

        $this->assertEquals(['Marija', 'Marko'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hey @Marija.'
        ]);

        $this->assertEquals('Hey <a href="/profiles/Marija">@Marija</a>.', $reply->body);
    }

    /** @test */
    public function it_knows_if_its_the_best_reply()
    {
        $reply = create(Reply::class);

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
