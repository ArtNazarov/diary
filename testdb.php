    <?php
    require_once(__DIR__ . '/calendar.php');
    require_once(__DIR__ . '/year_calendar.php');
    require_once(__DIR__ . '/nav_months.php');
    // Конфиг
    require_once(__DIR__ . '/config.php');

    function getConn(){
        $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    function displayData(){


    // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, situation, date FROM diary";
    $result = $conn->query($sql);
    $html = "<style>.note 
{float:left; border:thin solid #000; display:block; width:25%;height:auto;
padding:10px; margin:10px;
-webkit-border-bottom-right-radius: 10px;
-moz-border-radius-bottomright: 10px;
border-bottom-right-radius: 10px;
}
</style><div>";
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $rowid = $row["id"];
            $date = $row['date'];
            $timestamp = strtotime($date);

            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $html .= "<div class='note'> id: " . $row["id"]. " | Ситуация: " . mb_substr($row["situation"], 0, 22) . " | Дата " . $row["date"].
             "   <a href='/test/testdb.php?action=details&id=$rowid'>Прочитать</a>  ";
            $html .=  "<a href='/test/testdb.php?action=calendar&year=$year&month=$month'>В календарь</a></div>";
        }
    } else {
        $html = "0 results";
    }
    $html .= "<div style='clear:both'></div>";
    $conn->close();
    return $html;
    }

    function displayDetails(){

    $id = (int)$_GET['id'];

    // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM diary WHERE id=$id LIMIT 1";
    $result = $conn->query($sql);
    $html = "";
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //print_r($row);
            $html .= "id:<br/> " . $row["id"]. "<hr/> Ситуация:<br/> " . $row["situation"]. "<hr/> Дата " . $row["date"]. "<br>";
            // `thoughts`, `alternative`, `conclusion`, `date`, `emotion`, `emotion_level`, `tress`
            $html .= "Мысли:<br/> " . $row["thoughts"]. "<hr/> Альтернативы:<br/> " . $row["alternative"]. "<hr/> Эмоция: <br/> " . $row["emotion"]. "<hr>";
            $html .= "Уровень эмоции: " . $row["emotion_level"]. "<hr/> Паттерн: " . $row["tress"]. " <hr/> Вывод<br/>" . $row["conclusion"]. "<br>";
        }
    } else {
        $html = "0 results";
    }
    $conn->close();
    return $html;
    }
    
    function viewAdd(){
        $html = file_get_contents(__DIR__ . '/add.tpl');
        return $html;
    }

    function viewEdit(){
        $id = (int)$_GET['id'];
        // Create connection
        $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM diary WHERE id=$id LIMIT 1";
        $result = $conn->query($sql);
        $obj_resp = [];
        $situation = "";
        $date = "";
        $thoughts = "";
        $alternative = "";
        $emotion = "";
        $emotion_level = 0;
        $tress = "";
        $conclusion = "";
        $error = true;
        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                $situation = $row["situation"];
                $date = $row["date"];
                $thoughts = $row["thoughts"];
                $alternative = $row["alternative"];
                $emotion = $row["emotion"];
                $emotion_level = $row["emotion_level"];
                $tress = $row["tress"];
                $conclusion = $row["conclusion"];
                $error = false;
            }
        };
        $obj_resp['id'] = $id;
        $obj_resp['situation'] = $situation;
        $obj_resp['date'] = $date;
        $obj_resp['thoughts'] = $thoughts;
        $obj_resp['alternative'] = $alternative;
        $obj_resp['emotion'] = $emotion;
        $obj_resp['emotion_level'] = $emotion_level;
        $obj_resp['tress'] = $tress;
        $obj_resp['conclusion'] = $conclusion;
        $obj_resp['error'] = $error;
        $conn->close();
        return $obj_resp;
    }

    function filter($string){
        return strip_tags($string);
    }
    
    function delete(){
        $id = (int)$_GET['id'];
          $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        
        $html = "Connection failed: " . $conn->connect_error;
        return $html;
    }
         //echo "get max...<br/>";
      $sql = "DELETE FROM diary WHERE id=$id";
     if (true) { $result = $conn->query($sql);};
         $conn->close();
         $html = "Удалена запись номер $id";
         return $html;
    }
    
    function save(){
        //echo "filtering...<br/>";
        //print_r($_POST);
        $situation = filter($_POST['situation']);
        $thoughts = filter($_POST['thoughts']);
        $alternative = filter($_POST['alternative']);
        $conclusion = filter($_POST['conclusion']);
        $emotion_level = (int)$_POST['emotion_level'];
        $tress = filter($_POST['tress']);
        $date = $_POST['date'];
        $emotion = filter($_POST['emotion']);

        //    echo "try to connect...<br/>";
        $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
        // Check connection
        if ($conn->connect_error) {

            $html = "Connection failed: " . $conn->connect_error;
            return $html;
        }
        //echo "get max...<br/>";
        $sql = "SELECT MAX(id) as cnt FROM diary WHERE 1=1 LIMIT 1";
        $result = $conn->query($sql);
        //print_r($result);
        $row = $result->fetch_assoc();
        //print_r($row);
        $id = $row['cnt']+1;
        //echo $id;
        $sql = "INSERT INTO diary( id, situation, thoughts,  alternative,
            conclusion, emotion_level, tress, date, emotion)
            VALUES ( $id, '$situation', '$thoughts', '$alternative',
               '$conclusion', $emotion_level, '$tress', '$date', '$emotion')";

        //  echo "insertion...<br/>";
        $result = $conn->query($sql);
        $conn->close();
        $html = "Добавлено под номером $id";
        return $html;
    }

    function update(){
        //echo "filtering...<br/>";
        //print_r($_POST);
        $id = $_POST['id'];
        $situation = filter($_POST['situation']);
        $thoughts = filter($_POST['thoughts']);
        $alternative = filter($_POST['alternative']);
        $conclusion = filter($_POST['conclusion']);
        $emotion_level = (int)$_POST['emotion_level'];
        $tress = filter($_POST['tress']);
        $date = $_POST['date'];
        $emotion = filter($_POST['emotion']);

        //    echo "try to connect...<br/>";
        $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
        // Check connection
        if ($conn->connect_error) {

            $html = "Connection failed: " . $conn->connect_error;
            return $html;
        }

        $sql = "UPDATE diary SET
            situation=\"$situation\",
            thoughts=\"$thoughts\",  
            alternative=\"$alternative\",
            conclusion=\"$conclusion\", 
            emotion_level=$emotion_level, 
            tress=\"$tress\", 
            date=\"$date\",
            emotion=\"$emotion\"
           WHERE id=$id";
        $result = $conn->query($sql);
        $conn->close();
        $vars = [];
        $vars['sql'] = $sql;
        $vars['id'] = $id;
        $vars['content'] = "Cохранено $id";
        return $vars;
    }

    function viewCalendar(){
        isset($_GET['month']) ? $month = (int)$_GET['month'] : $month = '2';
        isset($_GET['year']) ? $year = (int)$_GET['year'] : $year = '2020';
        $calendar = output_calendar($month, $year, true, NULL);
        $vars = [];
        $vars['month'] = $month;
        $vars['year'] = $year;
        $vars['calendar'] = $calendar;
        $date = "$year-$month-1";
        $pm = nav_prev_month($date);
        $nm = nav_next_month($date);
        $vars['nmonth'] = $nm['m'];
        $vars['nyear'] = $nm['y'];
        $vars['pmonth'] = $pm['m'];
        $vars['pyear'] = $pm['y'];
        return $vars;
    }

    function viewYearCalendar(){
        isset($_GET['year']) ? $year = (int)$_GET['year'] : $year = '2020';
        $year_calendar = output_year_calendar($year);
        $vars = [];
        $vars['year'] = $year;
        $vars['year_calendar'] = $year_calendar;
        return $vars;
    }

    function make_view($flow, $variable, $value){
       return str_replace('%'.$variable.'%', $value, $flow);
    }

    function dispatch(){
    
    isset($_GET['action']) ? $action = $_GET['action'] : $action = 'display';
    $result = ['vars'=>[], 'use'=>''];
    switch($action){
        case 'display' : { $result['use']='tpl_gen'; $result['vars']['content'] = displayData(); break;}
        case 'details' : {
            
             $result['vars']['id'] = (int)$_GET['id'];
            $result['use']='tpl_details'; $result['vars']['content'] = displayDetails(); break;}
        case 'add'     : { $result['use']='tpl_add'; $result['vars']['content'] = viewAdd(); break;}
        case 'save'     : {  $result['use']='tpl_save';   $result['vars']['content'] = save(); break;}
        case 'update'     : {
            $vars = update();
            $result['use']='tpl_update';
            foreach ($vars as $name => $value){
                $result['vars'][$name] = $value;
            };

        break;}
        case 'delete'     : {  $result['use']='tpl_delete'; $result['vars']['content'] = delete(); 

        break;}
        case 'edit' :
        {
            $vars = viewEdit();
            $result['use']='tpl_edit';
            foreach ($vars as $name => $value){
                $result['vars'][$name] = $value;
            };

        break;
        }
        case 'calendar'     : {
            $result['use']='tpl_calendar';
            $vars = viewCalendar();
            foreach ($vars as $name => $value){
                $result['vars'][$name] = $value;
            };

            break;}
        case 'year_calendar'     : {
            $result['use']='tpl_year_calendar';
            $vars = viewYearCalendar();
            foreach ($vars as $name => $value){
                $result['vars'][$name] = $value;
            };

            break;}
        default : { $result['use']='tpl_gen';
                    $result['vars']['content'] = 'НЕИЗВЕСТНОЕ ДЕЙСТВИЕ';
                    break;}
     };
     return $result;
    }

    function view($templates, $res){
       $flow = $templates[$res['use']];

       foreach($res['vars'] as $var => $repl ){
           $flow = make_view($flow, $var, $repl);
       }
       return $flow;
    }

    function main(){
        $templates = [
            'boot' => file_get_contents(__DIR__ . '/boot.tpl'),
            'tpl_details' => file_get_contents(__DIR__ . '/details.tpl'),
            'tpl_edit' => file_get_contents(__DIR__ . '/edit.tpl'),
            'tpl_gen' => file_get_contents(__DIR__ . '/gen.tpl'),
            'tpl_save' => file_get_contents(__DIR__ . '/save.tpl'),
            'tpl_update' => file_get_contents(__DIR__ . '/update.tpl'),
            'tpl_delete' => file_get_contents(__DIR__ . '/delete.tpl'),
            'tpl_add' => file_get_contents(__DIR__ . '/add.tpl'),
            'tpl_calendar' => file_get_contents(__DIR__ . '/calendar.tpl'),
            'tpl_year_calendar' => file_get_contents(__DIR__ . '/year_calendar.tpl'),];
        $load = view($templates, dispatch());
        echo view($templates, [ 'vars'=>['load'=>$load], 'use'=>'boot']);
    }

    main();