<?php

require_once(__DIR__ . '/config.php');

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
    return $w;
}

function getWeekday($date) {
    $i = date('w', strtotime($date));
    ($i==0) ? $i = 6 : $i--;
    return $i;
}

function addFromDb(&$cal, $month, $year){
    $conn = getConn();
    $sql = "SELECT id, situation, date FROM diary WHERE month(date)=$month";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()){
        $date = $row['date'];
        $id = $row['id'];
        $situation = mb_substr($row['situation'], 0, 16);
        $place = getWeekday($date);
        $index_of_row=weekOfMonth($date);
        $text = $cal[$index_of_row][$place]['text'];
        $text = "<a href=/test/testdb.php?action=details&id=$id>$situation</a><br/>";
        $cal[$index_of_row][$place]['text'] .= $text;
    }
    $conn->close();
}
function calendar($month, $year){

$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$rows = ceil($days / 7)+1;
// Init widget
$calendar = [];
$index_of_row = 0;
for($r=0;$r<$rows;$r++){
	$calendar[$r] = [];
	for($d=0;$d<8;$d++){
	$calendar[$r][$d] = ["text"=>""];
	};
};

$index_of_row = 0;

for($i=1;$i<=$days;$i++){

	$date = "$year-$month-$i";
	$place = getWeekday($date);
    $index_of_row=weekOfMonth($date);

	$calendar[$index_of_row][$place] = ["date" => $date, "text"=>""];
	};
   addFromDb($calendar, $month, $year);
   // print_r( getWeekday("2020-2-1") . ' ' . weekOfMonth("2020-2-1") );
return $calendar;
};

function output_calendar($month, $year){
  $daynames = [ "Пн", "Вт",
  "Ср", "Чт", "Пт", "Сб", "Вс"];
  $cal = calendar($month, $year);
  $html = "<table border='1'>";
  for ($r=0;$r<count($cal);$r++){
     $html.="<tr>";
    if ($r==0){
   
    for ($d=0;$d<count($daynames);$d++){
      $html .= "<td>" . $daynames[$d] . "</td>";
    };

    };
    if ($r>0){
    for ($d=0;$d<count($daynames);$d++){

        $style = "";
        if ($d>5) { $style = " style=' background-color:#ff0000;' ";}

        if ($cal[$r][$d]['text']!==""){
            $style = " style=' background-color:#00ff00;' ";
        };

     if (isset( $cal[$r][$d]["date"] ))
     {
         $html.="<td $style>" . $cal[$r][$d]["date"] . "<br/>" . $cal[$r][$d]['text'] . "</td>";
     }
      else
        { $html.="<td $style> - </td>"; } ;

    };
    };
    $html .= "</tr>";
  };
  $html .= "</table>";
  return $html;
}



?>