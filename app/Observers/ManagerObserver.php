<?php

namespace App\Observers;

use App\Models\Manager;
use App\Mail\OrgRegisterationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ManagerObserver
{
    /**
     * Handle the Manager "created" event.
     *
     * @param  \App\Models\Manager  $manager
     * @return void
     */
    public function created(Manager $manager)
    {
        // Send email to the manager
        Log::info('Manager created: ' . $manager->name);
    }

    /**
     * Handle the Manager "updated" event.
     *
     * @param  \App\Models\Manager  $manager
     * @return void
     */
    public function updated(Manager $manager)
    {
        //
    }

    /**
     * Handle the Manager "deleted" event.
     *
     * @param  \App\Models\Manager  $manager
     * @return void
     */
    public function deleted(Manager $manager)
    {
        //
    }

    /**
     * Handle the Manager "restored" event.
     *
     * @param  \App\Models\Manager  $manager
     * @return void
     */
    public function restored(Manager $manager)
    {
        //
    }

    /**
     * Handle the Manager "force deleted" event.
     *
     * @param  \App\Models\Manager  $manager
     * @return void
     */
    public function forceDeleted(Manager $manager)
    {
        //
    }
}
