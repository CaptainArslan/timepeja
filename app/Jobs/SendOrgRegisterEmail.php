<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\OrgRegisterationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendOrgRegisterEmail implements ShouldQueue, shouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $email;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $details)
    {
        $this->email = $email;
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new OrgRegisterationEmail($this->details));
    }
}
