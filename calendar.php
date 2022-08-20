<?php

require_once(__DIR__ . '/config.php');

require_once(__DIR__ . '/calendar_helpers.php');


function addFromDb(&$cal, $month, $year, $username){
    $conn = getConn();
    $sql = "SELECT id, situation, date FROM diary WHERE username = ? AND month(date) = ? AND year(date) = ? ";
     
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $username, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()){
        $date = $row['date'];
        $id = $row['id'];
        $situation = mb_substr($row['situation'], 0, 16);
        $place = getWeekday($date);
        $index_of_row=weekOfMonth($date);
        $text = $cal[$index_of_row][$place]['text'];
        $text = "<a href=/testdb.php?action=details&id=$id>$situation</a><br/>";
        $cal[$index_of_row][$place]['text'] .= $text;
    }
    $conn->close();
}
function calendar($username, $month, $year){

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
   addFromDb($calendar, $month, $year, $username);
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

function output_calendar($username, $month, $year){
  $daynames = [ "Пн", "Вт",
  "Ср", "Чт", "Пт", "Сб", "Вс"];
  $cal = calendar($username, $month, $year);
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
                
                $date = @$cal[$r][$d]["date"];
                
     $link_for_adding = "<br/><a href=/testdb.php?action=add_details&month=$month&year=$year&date=$date> [ + ] </a><br/>";
           
     if (isset( $cal[$r][$d]["date"] ))
     {
         $html.="<td $style>" . $cal[$r][$d]["date"] . "<br/>" . $cal[$r][$d]['text'] . $link_for_adding . "</td>";
     }
      else
        { $html.="<td $style> </td>"; } ;

    };
    };
    $html .= "</tr>";
  };
  $html .= "</table>";
  return $html;
}



?>