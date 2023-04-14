<?php

namespace App\Observers;

use App\Models\Organization;
use App\Mail\OrgRegisterationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     *
     * @param  \App\Models\Organization  $organization
     * @return void
     */
    public function created(Organization $organization)
    {
        $details = [
            'title' => 'Mail from Stoppick',
            'name' => $organization->name,
            'email' => $organization->email,
            'phone' => $organization->phone,
            'otp' => $organization->otp,
            'body' => '',
        ];

        try {
            Mail::to($organization->email)->send(new OrgRegisterationEmail($details));
            Log::info('Email send succcesffuly');
        } catch (\Exception $e) {
            // Handle the exception here
            Log::info('Error Occured \n ' . $e->getMessage());
        }
        Log::info('organization created: ' . $organization->name);
    }

    /**
     * Handle the Organization "updated" event.
     *
     * @param  \App\Models\Organization  $organization
     * @return void
     */
    public function updated(Organization $organization)
    {
        //
    }

    /**
     * Handle the Organization "deleted" event.
     *
     * @param  \App\Models\Organization  $organization
     * @return void
     */
    public function deleted(Organization $organization)
    {
        //
    }

    /**
     * Handle the Organization "restored" event.
     *
     * @param  \App\Models\Organization  $organization
     * @return void
     */
    public function restored(Organization $organization)
    {
        //
    }

    /**
     * Handle the Organization "force deleted" event.
     *
     * @param  \App\Models\Organization  $organization
     * @return void
     */
    public function forceDeleted(Organization $organization)
    {
        //
    }
}
