<?php

namespace App\Observers;

use App\Subscriber;
use App\Traits\PropertyBaseTrait;

class SubscriberObserver
{
    use PropertyBaseTrait;

    /**
     * Handle the subscriber "created" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function created(Subscriber $subscriber)
    {
        if(settings('propertybase'))
        {
            // Send Data To Propertybase (New User)...
            $this->post_newsletter($subscriber);
        }
    }

    /**
     * Handle the subscriber "updated" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function updated(Subscriber $subscriber)
    {
        //
    }

    /**
     * Handle the subscriber "deleted" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function deleted(Subscriber $subscriber)
    {
        //
    }

    /**
     * Handle the subscriber "restored" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function restored(Subscriber $subscriber)
    {
        //
    }

    /**
     * Handle the subscriber "force deleted" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function forceDeleted(Subscriber $subscriber)
    {
        //
    }
}
