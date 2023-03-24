<?php


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
 *@param \Illuminate\Http\UploadedFile $image The uploaded image file
 *
 *@param string $folderName The name of the folder to store the image in
 *
 *@return string The generated filename of the uploaded image
 *
 *@throws \Exception if the image is invalid or an error occurs during the upload process
 */

function uploadImage($image, $folderName)
{
    // Check if the image is valid
    if (!$image->isValid()) {
        throw new \Exception('Invalid image.');
    }

    // Generate a unique filename for the image
    $filename = time() . '_' . $image->getClientOriginalName();

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
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}
