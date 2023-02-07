<?php
function print_data($array)
{
    echo "<div align='left'><pre>";
    if(is_array($array))
        print_r($array);
    else
        echo $array;
    echo "</pre></div>";
}