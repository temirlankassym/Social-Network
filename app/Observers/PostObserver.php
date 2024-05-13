<?php

namespace App\Observers;

use App\Interfaces\SubscriberInterface;
use App\Models\Post;

class PostObserver
{
    private $subscriber;

    public function __construct(SubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // retrieving all subscribers of the post's profile
        $users = $post->profile->subscribers;
        foreach($users as $user){
            // updating the state of the subscriber
            $this->subscriber->updateState($user->username, $user->subscriber);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
