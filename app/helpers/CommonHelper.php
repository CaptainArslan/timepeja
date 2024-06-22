<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrgRegisterEmailJob;

/**
 * [print_data description]
 *
 * @param   [type]  $array  [$array description]
 *
 * @return  [type]          [return description]
 */
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

/**
 * [formatDate description]
 *
 * @param   [type]  $date  [$date description]
 *
 * @return  [type]         [return description]
 */
function formatDate($date)
{
    $res = date("d/m/Y", strtotime($date));
    return $res;
}

/**
 * [formatTime description]
 *
 * @param   [type]  $time  [$time description]
 *
 * @return  [type]         [return description]
 */
function formatTime($time, $format = 'h:i A')
{
    $res = date($format, strtotime($time));
    return $res;
}

/**
 * [removeHypon description]
 *
 * @param   [type]  $string  [$string description]
 *
 * @return  [type]           [return description]
 */
function removeHypon($string)
{
    return str_replace('-', '', $string);
}

/**
 * [makeCnicFormat description]
 *
 * @param   [type]  $cnic_number  [$cnic_number description]
 *
 * @return  [type]                [return description]
 */
function makeCnicFormat($cnic_number)
{
    $cnic_formatted = substr($cnic_number, 0, 5) . '-' . substr($cnic_number, 5, 7) . '-' . substr($cnic_number, 12);
    return $cnic_formatted;
}

/**
 *Uploads an image to a specified folder and returns the filename
 *
 * @param \Illuminate\Http\UploadedFile $image The uploaded image file
 *
 * @param string $folderName The name of the folder to store the image in
 *
 * @return string The generated filename of the uploaded image
 *
 * @throws \Exception if the image is invalid or an error occurs during the upload process
 */
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

/**
 * Removes an image file from the specified folder in the public directory.
 *
 * @param string $imageName The name of the image file to remove.
 * @param string $folderName The name of the folder where the image file is stored.
 * @return void
 */
function removeImage($imageName, $folderName)
{
    $imagePath = public_path('uploads/' . $folderName . '/' . $imageName);
    // dd($imagePath);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Helper function to encode data as base64url
function base64url_encode($data)
{
    $base64 = base64_encode($data);
    $base64url = strtr($base64, '+/', '-_');
    return rtrim($base64url, '=');
}

// Helper function for to send job application email
/**
 * Undocumented function
 *
 * @param [type] $email
 * @param [type] $details
 * @return void
 */
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


/**
 * @param $title
 * @param $body
 * @param $device_token
 * @return bool|string|null
 */
function notification($title, $body, $device_token): bool|string|null
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
        return $response;
        Log::info($e->getMessage());
    }
    return null;
}

