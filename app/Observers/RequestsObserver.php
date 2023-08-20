<?php

namespace App\Observers;

use App\Models\Request;

class RequestsObserver
{
    /**
     * Handle the Request "created" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function created(Request $request)
    {
        //
    }

    /**
     * Handle the Request "updated" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function updated(Request $request)
    {
        //
    }

    /**
     * Handle the Request "deleted" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function deleted(Request $request)
    {
        //
    }

    /**
     * Handle the Request "restored" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function restored(Request $request)
    {
        //
    }

    /**
     * Handle the Request "force deleted" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function forceDeleted(Request $request)
    {
        //
    }
}
