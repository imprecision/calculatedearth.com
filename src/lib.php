<?php

$position_requested_x = null;
$position_requested_y = null;
$area_requested = null;
$zoomed = false;

if (isset($_SERVER['QUERY_STRING'])) {
    if ($_SERVER['QUERY_STRING'] == 'eu') {
        $area_requested = 'eu';
        $zoomed = true;
    } elseif ($_SERVER['QUERY_STRING'] == 'us') {
        $area_requested = 'us';
        $zoomed = true;
    } elseif ($_SERVER['QUERY_STRING'] == 'ea') {
        $area_requested = 'ea';
        $zoomed = true;
    } else {
        $query_string = explode("x", $_SERVER['QUERY_STRING']);
        if (is_array($query_string) && (count($query_string) == 2)) {
            if (is_numeric($query_string[0]) && is_numeric($query_string[1])) {
                $position_requested_x = (int)$query_string[0];
                $position_requested_y = (int)$query_string[1];
                $zoomed = true;
            }
        }
    }
}
