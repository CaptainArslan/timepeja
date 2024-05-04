<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendOtp implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $otp;
    protected $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($otp, $phone)
    {
        $this->otp = $otp;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $factory = (new Factory)->withServiceAccount('/path/to/service_account.json');
        $messaging = $factory->createMessaging();

        $otp = random_int(100000, 999999);

        $message = CloudMessage::withTarget('token', $user->firebase_token)
            ->withNotification(Notification::create('OTP Verification', "Your OTP is $otp"));
        $messaging->send($message);
    }
}
