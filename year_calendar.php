<?php

require_once(__DIR__ . '/config.php');

require_once(__DIR__ . '/calendar_helpers.php');

function year_addFromDb(&$cal, $year){
    $conn = getConn();
    $sql = "SELECT id, situation, date FROM diary WHERE year(date)=$year";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()){
        $date = $row['date'];
        $id = $row['id'];
        $situation = mb_substr($row['situation'], 0, 16);
        $month = intval( @date('n', $row['date']) );
       // print_r($month);
        $place = getWeekday($date);
        $index_of_row=weekOfMonth($date);
        $append = "<a href=/test/testdb.php?action=details&id=$id>$situation</a><br/>";
        if (isset( $cal[$month][$index_of_row][$place]['text'] )){
        $cal[$month][$index_of_row][$place]['text'] .= $append;
            } else { $cal[$month][$index_of_row][$place]['text'] = $append;};
    };
   //var_dump($cal[1]);
    $conn->close();
}
function year_calendar($year){
    $year_calendar = [];
    for ($month_index=0;$month_index<12;$month_index++){
$month = $month_index + 1;
$days = cal_days_in_month(CAL_GREGORIAN, $month_index+1, $year);
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

array_push($year_calendar, $calendar);

$index_of_row = 0;

for($i=1;$i<=$days;$i++){

	$date = "$year-$month-$i";
	$place = getWeekday($date);
    $index_of_row=weekOfMonth($date);

	$year_calendar[$month_index][$index_of_row][$place] = ["date" => $date, "text"=>""];
	};

   // print_r( getWeekday("2020-2-1") . ' ' . weekOfMonth("2020-2-1") );
        };
    year_addFromDb($year_calendar,  $year);
    return $year_calendar;
};

function year_empty_row($cal, $month_index){
    $empty = true;
    // print_r($cal[1]);
    //print_r($cal[1][0]);
    for ($i=0;$i<7  ;$i++){
        $empty = (!isset($cal[$month_index][1][$i]['date']))&&($empty);
    };
    return $empty;
}

function output_year_calendar($year){
  $daynames = [ "Пн", "Вт",
  "Ср", "Чт", "Пт", "Сб", "Вс"];
  $month_names = ['Январь', 'Февраль', 'Март',
  'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
      'Октябрь', 'Ноябрь', 'Декабрь'];
  // $month_names
  $cal = year_calendar($year);
  $year_html = "";
  for ($month_index = 0;$month_index<12;$month_index++) {
      $mn = $month_index+1;
      $html = "<table border='1'><h2><a href='/test/testdb.php?action=calendar&year=$year&month=$mn'>$month_names[$month_index]</a></h2>";
      for ($r = 0; $r < count($cal[$month_index]); $r++) {
          $html .= "<tr>";
          if ($r == 0) {

              for ($d = 0; $d < count($daynames); $d++) {
                  $html .= "<td>" . $daynames[$d] . "</td>";
              };

          };
          if (($r==1)&&(year_empty_row($cal, $month_index))){continue;};
          if ($r > 0) {
              for ($d = 0; $d < count($daynames); $d++) {

                  $style = "";
                  if ($d > 5) {
                      $style = " style=' background-color:#ff0000;' ";
                  }

                  if (isset($cal[$month_index][$r][$d]['text'])) {
                      if ($cal[$month_index][$r][$d]['text'])
                      { $style = " style=' background-color:#00ff00;' ";};
                  };

                  if (isset($cal[$month_index][$r][$d]["date"])) {
                      $html .= "<td $style>" . $cal[$month_index][$r][$d]["date"] . "<br/>" . $cal[$month_index][$r][$d]['text'] . "</td>";
                  } else {
                      $html .= "<td $style> - </td>";
                  };

              };
          };
          $html .= "</tr>";
      };
      $html .= "</table>";
      $year_html .= $html;
  };
  return $year_html;
}



?>