<?php

function weekOfMonth($date) {
// estract date parts
list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

// current week, min 1
$w = 1;

// for each day since the start of the month
for ($i = 1; $i <= $d; ++$i) {
// if that day was a sunday and is not the first day of month
if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
// increment current week
++$w;
}
};
if (getWeekday($date)==6){
$w--;
}
// now return
return $w+1;
}


function getWeekday($date) {
$i = date('w', strtotime($date));
($i==0) ? $i = 6 : $i--;
return $i;
}
?>