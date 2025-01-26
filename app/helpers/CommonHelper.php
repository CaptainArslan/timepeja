<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrgRegisterEmailJob;

function print_data($array)
{
    echo "<div align='left'><pre>";
    if (is_array($array)) {
        print_r($array);
    } else {
        echo $array;
    }
    echo "</pre></div>";
}

function formatDate($date)
{
    $res = date("d/m/Y", strtotime($date));
    return $res;
}

function formatTime($time, $format = 'h:i A')
{
    $res = date($format, strtotime($time));
    return $res;
}

function removeHypon($string)
{
    return str_replace('-', '', $string);
}

function makeCnicFormat($cnic_number)
{
    $cnic_formatted = substr($cnic_number, 0, 5) . '-' . substr($cnic_number, 5, 7) . '-' . substr($cnic_number, 12);
    return $cnic_formatted;
}

function uploadImage($image, $folderName, $defaultName = null)
{
    // Check if the image is valid
    if (!$image->isValid()) {
        throw new \Exception('Invalid image.');
    }

    $extension = $image->getClientOriginalExtension();

    // Generate a unique filename for the image
    $filename = uniqid() . '_' . time() . '_' . $defaultName . '.' . $extension;

    if (!is_dir(public_path('uploads/' . $folderName))) {
        // create the directory if it does not exist
        mkdir(public_path('uploads/' . $folderName), 0777, true);
    }

    // Upload the image to the specified folder
    try {
        $image->move(public_path('uploads/' . $folderName), $filename);
    } catch (\Exception $e) {
        throw new \Exception('Error uploading image: ' . $e->getMessage());
    }

    // Return the filename so it can be saved to a database or used in a view
    return $filename;
}

function removeImage($imageName, $folderName)
{
    $imagePath = public_path('uploads/' . $folderName . '/' . $imageName);
    // dd($imagePath);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

function base64url_encode($data)
{
    $base64 = base64_encode($data);
    $base64url = strtr($base64, '+/', '-_');
    return rtrim($base64url, '=');
}

function emailsendingJob($email, $object)
{
    $details = [
        'title' => 'Stoppick Registeration',
        'name' => $object->name,
        'email' => $object->email,
        'phone' => $object->phone,
        'otp' => $object->otp,
    ];
    dispatch(new SendOrgRegisterEmailJob($email, $details));
}

function getGoogleApi()
{
    try {
        $data = Setting::where('platform', 'google_map')->first();
        if ($data)
            return $data->credentials;
    } catch (\Exception $e) {
        return null;
    }
}

function getPdfLogo()
{
    return asset('images/logo.png');
}

function getPaginated($limit = 10)
{
    return $limit;
}

function notification($title, $body, $device_token)
{
    try {
        $SERVER_API_KEY = config('app.firebase_key');

        $data = [
            "to" => $device_token,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],

        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        Log::info($response);
    } catch (\Exception $e) {
        Log::info($e->getMessage());
        return $response;
    }
}

