<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SMSService
{
    protected $apiUrl;
    protected $loginId;
    protected $loginPassword;
    protected $mask;
    protected $UniCode;
    protected $shortCodePrefered;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.url') . '/SendQuickSMS';
        $this->loginId = config('services.sms.login_id');
        $this->loginPassword = config('services.sms.login_password');
        $this->mask = config('services.sms.mask');
        $this->UniCode = config('services.sms.unicode');
        $this->shortCodePrefered = config('services.sms.short_code_prefered');
    }

    /**
     * Send SMS via API
     *
     * @param string $phone Receiver's phone number
     * @param string $message Message to be sent
     * @return array
     */
    public function sendSMS($phone, $message)
    {
        $payload = [
            "loginId" => $this->loginId,
            "loginPassword" => $this->loginPassword,
            "Destination" => $phone,
            "Mask" => $this->mask,
            "Message" => $message,
            "UniCode" => $this->UniCode,
            "ShortCodePrefered" => $this->shortCodePrefered,
        ];

        try {
            $response = Http::post($this->apiUrl, $payload);
            $statusCode = $response->status();
            $responseBody = trim($response->body()); // Get response as a string

            Log::info("SMS API Raw Response", [
                'status' => $statusCode,
                'response' => $responseBody,
                'phone' => $phone,
            ]);

            // Parse the response: "0|success|5394948463"
            $responseParts = explode('|', $responseBody);
            $statusCode = $responseParts[0] ?? null;
            $statusMessage = $responseParts[1] ?? 'Unknown response';
            $transactionId = $responseParts[2] ?? null;

            if ($statusCode === '0' && strtolower($statusMessage) === 'success') {
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'transaction_id' => $transactionId,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to send SMS',
                'error' => $statusMessage,
            ];
        } catch (\Exception $e) {
            Log::error("SMS Sending Failed", [
                'error' => $e->getMessage(),
                'phone' => $phone,
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => 'Exception occurred while sending SMS',
                'error' => $e->getMessage(),
            ];
        }
    }
}
