<?php

namespace App\Listeners;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Events\FcmNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Google_Client as GoogleClient;

class FcmNotificationListener
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
     * @param  \App\Events\FcmNotificationEvent  $event
     * @return void
     */
    public function handle(FcmNotificationEvent $event)
    {
        // Retrieve tokens, title, and body from the event
        $tokens = $event->fcmTokens;
        $title = $event->title;
        $body = $event->body;
        $data = $event->data;

        Log::info('FCM Notification Tokens: ', [
            'tokens' => $tokens,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        if (empty($tokens)) {
            Log::warning('No device tokens provided');
        }

        try {
            $projectId = config('services.fcm.project_id');
            $credentialsFilePath = config('services.fcm.credentials_file_path');

            Log::info("FCM Project Details: ", [
                'project_id' => $projectId,
                'credentials_file_path' => $credentialsFilePath,
            ]);


            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();

            $access_token = $token['access_token'];
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];

            // Prepare the base payload
            $baseData = [
                "message" => [
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    "data" => is_array($data) ? array_map('strval', $data) : [],
                ]
            ];

            foreach ($tokens as $fcm) {
                $data = $baseData;
                $data['message']['token'] = $fcm; // Set token for each request
                $payload = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);

                if ($err) {
                    Log::error("FCM Notification Error: " . $err);
                } else {
                    Log::info("FCM Notification Response: " . $response);
                }
            }
        } catch (Exception $e) {
            Log::error("Exception in FCM Notification: " . $e->getMessage());
        }
    }
}
