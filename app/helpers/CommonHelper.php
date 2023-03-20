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
function formatTime($time, $format = 'H:m:s')
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
 * [moveImageGetName description]
 *
 * @param   Request  $request    [$request description]
 * @param   [type]   $inputName  [$inputName description]
 * @param   [type]   $path       [$path description]
 *
 * @return  [type]               [return description]
 */
function saveImageGetName($request, $inputName, $path)
{
    if ($request->hasFile($inputName)) {
        $file = $request->file($inputName);
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('uploads/' . $path), $filename);
        return $filename;
    } else {
        return null;
    }
}
