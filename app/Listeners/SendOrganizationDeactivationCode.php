<?php

namespace App\Listeners;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\OrganizationAccountDeactivateCode;
use App\Events\OrganizationAccountDeactivationRequest;

class SendOrganizationDeactivationCode implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrganizationAccountDeactivationRequest  $event
     * @return void
     */
    public function handle(OrganizationAccountDeactivationRequest $event)
    {
        try {
            Mail::to($event->organization->email)
                ->send(new OrganizationAccountDeactivateCode($event->organization));
        } catch (Throwable $th) {
            Log::error('Error occurred while sending email to organization: ' . $th->getMessage());
        }
    }
}
