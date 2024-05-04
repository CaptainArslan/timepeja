<?php

namespace App\Observers;

use App\Models\Guardian;

class GuardianObserver
{
    /**
     * Handle the Guardian "created" event.
     *
     * @param  \App\Models\Guardian  $guardian
     * @return void
     */
    public function created(Guardian $guardian)
    {
        //
    }

    /**
     * Handle the Guardian "updated" event.
     *
     * @param  \App\Models\Guardian  $guardian
     * @return void
     */
    public function updated(Guardian $guardian)
    {
        //
    }

    /**
     * Handle the Guardian "deleted" event.
     *
     * @param  \App\Models\Guardian  $guardian
     * @return void
     */
    public function deleted(Guardian $guardian)
    {
        //
    }

    /**
     * Handle the Guardian "restored" event.
     *
     * @param  \App\Models\Guardian  $guardian
     * @return void
     */
    public function restored(Guardian $guardian)
    {
        //
    }

    /**
     * Handle the Guardian "force deleted" event.
     *
     * @param  \App\Models\Guardian  $guardian
     * @return void
     */
    public function forceDeleted(Guardian $guardian)
    {
        //
    }
}
