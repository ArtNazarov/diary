<?php

function nav_prev_month($date){
    $pmonth = date('n', strtotime($date ." -1 month"));
    $pyear = date('Y', strtotime($date ." -1 month"));
    return
        [
            'm' => $pmonth,
            'y' => $pyear
        ];
}

function nav_next_month($date){
    $nmonth = date('n', strtotime($date ." +1 month"));
    $nyear = date('Y', strtotime($date ." +1 month"));
    return
        [
            'm' => $nmonth,
            'y' => $nyear
        ];
}

?>