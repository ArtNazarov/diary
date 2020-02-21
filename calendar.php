<?php

require_once(__DIR__ . '/config.php');

require_once(__DIR__ . '/calendar_helpers.php');


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

function empty_row($cal){
    $empty = true;
   // print_r($cal[1]);
   // print_r($cal[1][0]);
    for ($i=0;$i<7;$i++){
        $empty = (!isset($cal[1][$i]['date']))&&($empty);
    };
    return $empty;
}

function output_calendar($month, $year){
  $daynames = [ "Пн", "Вт",
  "Ср", "Чт", "Пт", "Сб", "Вс"];
  $cal = calendar($month, $year);
 // $em = (empty_row($cal)===false);
  $html = "<table border='1'>";
  for ($r=0;$r<count($cal);$r++){
     $html.="<tr>";
    if ($r==0){
   
    for ($d=0;$d<count($daynames);$d++){
      $html .= "<td>" . $daynames[$d] . "</td>";
    };

    };
      if (($r==1) && empty_row($cal)){continue;};
    if ($r>0){


    for ($d=0;$d<count($daynames);$d++){

        $style = "";
        if ($d>5) { $style = " style=' background-color:#ff0000;' ";}

        if (isset($cal[$r][$d]['text']))
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