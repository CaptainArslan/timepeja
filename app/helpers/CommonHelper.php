<?php

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
    $res = date("Y-m-d", strtotime($date));
    return $res;
}

function formatTime($time)
{
    $res = date("H:m:s", strtotime($time));
    return $res;
}
