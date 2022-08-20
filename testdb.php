    <?php
    session_start();
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

    $user = getUser();
    if ($user['logged'])
    {
      
        $username = $user['user'];
    
    
    
    
    // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT id, situation, date FROM diary WHERE username=?";
    $stmt = $conn->prepare( $sql );
    
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
        
    $html = " <style>.note 
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
            $html .= "<div class='note'>  Ситуация: " . mb_substr($row["situation"], 0, 22) . " <br/> Дата " . $row["date"].
             " <br/>   <a href='/testdb.php?action=details&id=$rowid'>Прочитать</a>  ";
            $html .=  " <br/><a href='/testdb.php?action=calendar&year=$year&month=$month'>В календарь</a></div>";
        }
    } else {
        $html = "0 results";
    }
    $html .= "<div style='clear:both'></div>";
    $conn->close(); }
 else {
     $html = '... для просмотра нужно войти под своим логином';
 }
    
    return $html;
    }
    
    function UserForm(){
        $user = getUser();
        $username = $user['user'];
        if (!$user['logged']){
            $uform = '<a href="/testdb.php?action=register">регистрация нового пользователя</a>
<a href="/testdb.php?action=login">вход пользователя</a>';
        }
        else
        {
            $uform = "Вошли под пользователем $username <a href='/logout.php'>Выход</a>";
            
        }
        return $uform;
    }
    
    function getUserActions(){
        $user = getUser();
        if ($user['logged']){
            $username = $user['user'];
            $html = "<a href='/testdb.php?action=add'>Добавить новую для $username </a> ";
        }
 else {
            $html = '<i>для действий с дневником нужно войти на сайт</i>';};
    return $html;
    }
    
    function displayLogin(){
        return "";
    }
    
    function displayRegister(){
        return "";
    }
    
    function getUser(){
        $user = '';
        if (isset($_SESSION['user'])){
            $user = $_SESSION['user'];
            $result = ['user'=>$user, 'logged'=>true]; 
        } 
        else { $result = ['user'=>'', 'logged'=>false]; }  
        if (''===$user) {$result['logged']=false; };
     return  $result;   
     
     
        }
    
    function tryLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        
        
        
 // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username=? AND password=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) { 
        $result = "такого пользователя нет! <a href='/'> на главную </a> "; 
        $_SESSION['user']='';
        
    }    
    else {
        $result = "Вход успешен. <a href='/'>Перейти к дневнику $username </a> ";
        $_SESSION['user']=$username;
    }
        
        return $result;
    }
    
    function tryRegister(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $details = $_POST['details'];
        
        
 // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        
          $sql = "INSERT INTO users( username, password, name,  details )
            VALUES ( ? , ? , ?, ?)";
         $stmt=$conn->prepare($sql);
         $stmt->bind_param("ssss", $username, $password, $name, $details); 
         $stmt->execute();
         $res = $stmt->get_result();
        $conn->close();
        $result = "...пользователь $username был создан";
    }
    else {
        $result = 'такой пользователь уже есть';
    }
        return $result;
    }
    
    function degree($val){
        $arr = ['нет', 'легкая', 'средняя', 'тяжелая'];
        return $arr[$val];
    }

    function displayDetails(){
    $user = getUser();
    if ($user['logged']) {
    $id = (int)$_GET['id'];

    // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM diary WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $html = "";
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //print_r($row);
            $html .= "id:<br/> " . $row["id"]. "<hr/> Ситуация:<br/> " . $row["situation"]. "<hr/> Дата " . $row["date"]. "<br>";
            // `thoughts`, `alternative`, `conclusion`, `date`, `emotion`, `emotion_level`, `tress`
            $html .= "Мысли:<br/> " . $row["thoughts"]. "<hr/> Альтернативы:<br/> " . $row["alternative"]. "<hr/> Эмоция: <br/> " . $row["emotion"]. "<hr>";
            
            $html .= "Уровень эмоции: " . $row["emotion_level"]. "<hr/> Паттерн: " . $row["tress"]. " <hr/> Вывод<br/>" . $row["conclusion"]. "<br>";
            $html .= "<h3>Карточка состояния</h3>";
            $html .= "Длительность сна, часов: " . $row["sleep_hours"] . "<br/>";
            $html .= "Выраженность депрессии: " . degree($row['dep_val']) . "<br/>";
            $html .= "Выраженность мании: " . degree($row['dep_val']) . "<br/>";
            $html .= "Степень тревоги: " . degree( $row['anx_val']) . "<br/>";
            $html .= "Степень раздраженности: " . degree( $row['vex_val'] ) . "<br/>";
        }
    } else {
        $html = "0 results";
    }
    $conn->close(); } else { $html = '...зайдите под логином'; };
    return $html;
    }
    
   
    
    function viewAdd(){
       $form = file_get_contents(__DIR__ . '/add.tpl');
           $user = getUser();
    if ($user['user']!=='') {
    $html = 'Новая заметка для '. $user['user'] . ' <hr/> ' . $form; }
    else { $html = '... зайдите под логином'; };
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

        $sql = "SELECT * FROM diary WHERE id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
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
                $sleep_hours = $row["sleep_hours"];
                $dep_val = $row['dep_val'];
                $vex_val = $row['vex_val'];
                $anx_val = $row['anx_val'];
                $man_val = $row['man_val'];
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
        $obj_resp['sleep_hours'] = $sleep_hours;
        $obj_resp['vex_val'] = $vex_val;
        $obj_resp['anx_val'] = $anx_val;
        $obj_resp['man_val'] = $man_val;
        $obj_resp['dep_val'] = $dep_val;
        $obj_resp['error'] = $error;
        $conn->close();
        return $obj_resp;
    }
    
    function add_Details(){
        $obj_resp = array();
        $obj_resp['logged'] = true;
        $user = getUser();
        if ($user['logged']) {
            $obj_resp['logged'] = false;
        };
        
        $obj_resp["date"] = $_GET['date'];
        $obj_resp['month'] = $_GET['month'];
        $obj_resp['year'] = $_GET['year'];
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
      
      $sql = "DELETE FROM diary WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();
     if (true) { $result = $stmt->get_result();};
         $conn->close();
         $html = "Удалена запись номер $id";
         return $html;
    }
    
    function save(){
        $user = getUser();
        $username = $user['user'];
        
        
        $situation = filter($_POST['situation']);
        $thoughts = filter($_POST['thoughts']);
        $alternative = filter($_POST['alternative']);
        $conclusion = filter($_POST['conclusion']);
        $emotion_level = (int)$_POST['emotion_level'];
        $tress = filter($_POST['tress']);
        $date = $_POST['date'];
        $sleep_hours = (int) $_POST['sleep_hours'];
        $emotion = filter($_POST['emotion']);
        $dep_val = (int) $_POST['dep_val'];
        $vex_val = (int) $_POST['vex_val'];
        $anx_val = (int) $_POST['anx_val'];
        $man_val =  (int) $_POST['man_val'];
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
        $sql = "INSERT INTO diary( 
                id, username, situation, thoughts,  alternative,
                conclusion, emotion_level, tress, date, emotion, 
                sleep_hours, dep_val, anx_val, man_val, vex_val)
            VALUES ( ?, ?, ?, ?, ?, 
                     ?, ?, ?, ?, ?, 
                     ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param(
                "isssssssssiiiii",
               $id,  $username, $situation, $thoughts, $alternative,
               $conclusion, $emotion_level, $tress, $date, $emotion, 
               $sleep_hours, $dep_val, $anx_val, $man_val, $vex_val);
        $stmt->execute();
        //  echo "insertion...<br/>";
        $result = $stmt->get_result($sql);
        $conn->close();
        $html = "Добавлено под номером $id";
        return $html;
    }
    
    
    function save_details(){
        $user = getUser();
        $username = $user['user'];
        
        
        $situation = filter($_POST['situation']);
        $thoughts = filter($_POST['thoughts']);
        $alternative = filter($_POST['alternative']);
        $conclusion = filter($_POST['conclusion']);
        $emotion_level = (int)$_POST['emotion_level'];
        $tress = filter($_POST['tress']);
        $date = $_POST['date'];
        $sleep_hours = (int) $_POST['sleep_hours'];
        $emotion = filter($_POST['emotion']);
        $dep_val = (int) $_POST['dep_val'];
        $vex_val = (int) $_POST['vex_val'];
        $anx_val = (int) $_POST['anx_val'];
        $man_val =  (int) $_POST['man_val'];
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
        $sql = "INSERT INTO diary( 
                id, username, situation, thoughts,  alternative,
                conclusion, emotion_level, tress, date, emotion, 
                sleep_hours, dep_val, anx_val, man_val, vex_val)
            VALUES ( ?, ?, ?, ?, ?, 
                     ?, ?, ?, ?, ?, 
                     ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param(
                "isssssssssiiiii",
               $id,  $username, $situation, $thoughts, $alternative,
               $conclusion, $emotion_level, $tress, $date, $emotion, 
               $sleep_hours, $dep_val, $anx_val, $man_val, $vex_val);
        $stmt->execute();
        //  echo "insertion...<br/>";
        $result = $stmt->get_result();
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
        
        $sleep_hours = (int) $_POST['sleep_hours'];
        $vex_val = (int) $_POST['vex_val'];
        $anx_val = (int) $_POST['anx_val'];
        $man_val = (int) $_POST['man_val'];
        $dep_val = (int) $_POST['dep_val'];
        //    echo "try to connect...<br/>";
        $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
        // Check connection
        if ($conn->connect_error) {

            $html = "Connection failed: " . $conn->connect_error;
            return $html;
        }
        
        $sql = "UPDATE diary SET
            situation = ?,
            thoughts = ?,  
            alternative = ?,
            conclusion = ?, 
            emotion_level = ?, 
            tress = ?, 
            date = ?,
            emotion = ?,
            sleep_hours = ?,
            vex_val = ?,
            dep_val = ?,
            anx_val = ?,
            man_val = ?
           WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param( "ssssisssiiiiii", 
                
                $situation ,
             $thoughts,  
             $alternative,
            $conclusion, 
            $emotion_level, 
             $tress, 
             $date,
            $emotion,
             $sleep_hours,
            $vex_val,
             $dep_val,
             $anx_val,
            $man_val,
            $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        $vars = [];
        $vars['sql'] = '(запрос выполнен)'; //$sql;
        $vars['id'] = $id;
        $vars['content'] = "Cохранено $id";
        return $vars;
    }

    function viewCalendar(){
        $user = getUser();
        $username = $user['user'];
        
        isset($_GET['month']) ? $month = (int)$_GET['month'] : $month = '2';
        isset($_GET['year']) ? $year = (int)$_GET['year'] : $year = '2020';
        $calendar = output_calendar($username, $month, $year, true, NULL);
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
        
        $calendar_template = file_get_contents(__DIR__ . '/calendar.tpl');
        
        $calendar_html = $calendar_template;
        
       foreach($vars as $var => $repl ){
           $calendar_html = make_view($calendar_html, $var, $repl);
       }
       
       return $calendar_html;
        
    }

    function viewYearCalendar(){
        $user = getUser();
        $username = $user['user'];
        isset($_GET['year']) ? $year = (int)$_GET['year'] : $year = '2020';
        $year_calendar = output_year_calendar($username, $year);
        $vars = [];
        $vars['year'] = $year;
        $vars['year_calendar'] = $year_calendar;
        
        
         $calendar_template = file_get_contents(__DIR__ . '/year_calendar.tpl');
        
        $calendar_html = $calendar_template;
        
       foreach($vars as $var => $repl ){
           $calendar_html = make_view($calendar_html, $var, $repl);
       }
       
       return $calendar_html;
        
    }

    function make_view($flow, $variable, $value){
       return str_replace('%'.$variable.'%', $value, $flow);
    }

    function dispatch(){
    
    isset($_GET['action']) ? $action = $_GET['action'] : $action = 'display';
    $result = ['vars'=>[], 'use'=>''];
    switch($action){
        // user management
        case 'login' : { 
            $result['use'] = 'tpl_login';
            $result['vars']['content'] = displayLogin();
            break;
        }
        
        case 'register' : { 
            $result['use'] = 'tpl_register';
            $result['vars']['content'] = displayRegister();
            break;
        }
        
        case 'try_login' : {
            $result['use'] = 'tpl_try_login';
            $result['vars']['result'] = tryLogin();
            break;
        }
        
       case 'try_register' : {
            $result['use'] = 'tpl_try_register';
            $result['vars']['result'] = tryRegister();
            break;
        }
        
        
        
        case 'display' : { $result['use']='tpl_gen'; 
        
        $result['vars']['content'] = displayData();
        $result['vars']['user_actions'] = getUserActions();
        break;}
        case 'details' : {
            
             $result['vars']['id'] = (int)$_GET['id'];
            $result['use']='tpl_details'; $result['vars']['content'] = displayDetails(); break;}
        case 'add'     : { $result['use']='tpl_gen'; $result['vars']['content'] = viewAdd();  break;}
        case 'save'     : {  $result['use']='tpl_save';   $result['vars']['content'] = save(); break;}
        case 'save_details'     : {  $result['use']='tpl_save';   $result['vars']['content'] = save_details(); break;}
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
        case 'add_details' : {
             $vars = add_Details();
        
            
            
            
            $result['use']='tpl_add_details';
            foreach ($vars as $name => $value){
                $result['vars'][$name] = $value;
            };
            break;
        }
            
        case 'calendar'     : {
            $result['use']='tpl_gen';
            $result['vars']['content'] = viewCalendar();
            
            break;}
        case 'year_calendar'     : {
            $result['use']='tpl_gen';
            $result['vars']['content'] = viewYearCalendar();
        

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
        
            'tpl_calendar' => file_get_contents(__DIR__ . '/calendar.tpl'),
            'tpl_year_calendar' => file_get_contents(__DIR__ . '/year_calendar.tpl'),
            'tpl_register'=> file_get_contents(__DIR__ . '/register.tpl'),
            'tpl_login'=>file_get_contents(__DIR__ . '/login.tpl'),
            'tpl_try_register'=> file_get_contents(__DIR__ . '/try_register.tpl'),
            'tpl_try_login'=>file_get_contents(__DIR__ . '/try_login.tpl'),
            'tpl_add_details' => file_get_contents(__DIR__ . '/add_details.tpl'),
            'tpl_save_details' => file_get_contents(__DIR__ . '/save_details.tpl')
            ];
       
        $load = view($templates, dispatch());
        echo view($templates, [ 'vars'=>['load'=>$load, 'user_form'=>UserForm()], 'use'=>'boot']);
    }

    main();