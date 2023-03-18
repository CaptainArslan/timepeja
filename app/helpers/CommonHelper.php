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
function formatTime($time)
{
    $res = date("H:m:s", strtotime($time));
    return $res;
}

/**
 * [formatTimeToAmPm description]
 *
 * @param   [type]  $time  [$time description]
 *
 * @return  [type]         [return description]
 */
function formatTimeToAmPm($time)
{
    return  date('g:i A', strtotime($time));
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
