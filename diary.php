    <?php
    // Replace your data
    define("dbname", '');
    define("dbuser", "");
    define("dbpass", '');
    define("dbhost", '');


    function displayData(){


    // Create connection
    $conn = new mysqli(dbhost, dbuser, dbpass, dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, situation, date FROM diary";
    $result = $conn->query($sql);
    $html = "";
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $rowid = $row["id"];
            $html .= "id: " . $row["id"]. " | ��������: " . substr($row["situation"], 0, 22) . " | ���� " . $row["date"].
             "   <a href='/test/testdb.php?action=details&id=$rowid'>���������</a> <br>";
        }
    } else {
        $html = "0 results";
    }
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
            $html .= "id:<br/> " . $row["id"]. "<hr/> ��������:<br/> " . $row["situation"]. "<hr/> ���� " . $row["date"]. "<br>";
            // `thoughts`, `alternative`, `conclusion`, `date`, `emotion`, `emotion_level`, `tress`
            $html .= "�����:<br/> " . $row["thoughts"]. "<hr/> ������������:<br/> " . $row["alternative"]. "<hr/> ������: <br/> " . $row["emotion"]. "<hr>";
            $html .= "������� ������: " . $row["emotion_level"]. "<hr/> �������: " . $row["tress"]. " <hr/> �����<br/>" . $row["conclusion"]. "<br>";
        }
    } else {
        $html = "0 results";
    }
    $conn->close();
    return $html;
    }
    
    function viewAdd(){
        $html = file_get_contents(__DIR__ . '/add.html');
        return $html;
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
         $html = "������� ������ ����� $id";
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
    $html = "��������� ��� ������� $id";
    return $html;
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
        case 'delete'     : {  $result['use']='tpl_delete'; $result['vars']['content'] = delete(); 
       
        break;}
        default : { $result['use']='tpl_gen'; $result['vars']['content'] = '����������� ��������'; break;}
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
        $templates = [ 'boot' => file_get_contents(__DIR__ . '/boot.html')
            ,
                       'tpl_gen' => '<h1>������� �� - ������ �������</h1>    %content%     <hr/> <a href="/test/testdb.php?action=add">�����</a> <hr/>   <b>������ 0.01</b>',
                       'tpl_details' => "<h1>������� �� - ����� �������</h1>    %content%     <hr/>  <a href='/test/testdb.php?action=delete&id=%id%'>�������...</a> | <a href='/test/testdb.php?action=display'>�� �������</a> <hr/>   <b>������ 0.01</b>",
                       'tpl_save' => '<h1>������� �� - ����������...</h1>    %content%     <hr/>  <a href="/test/testdb.php?action=display">�� �������</a> <hr/>   <b>������ 0.01</b>',
                        'tpl_delete' => '<h1>������� �� - ��������...</h1>    %content%     <hr/>  <a href="/test/testdb.php?action=display">�� �������</a> <hr/>   <b>������ 0.01</b>',
                       'tpl_add' => '<h1>������� �� - �������� �����</h1>    %content%     <hr/>     <b>������ 0.01</b>'];
        $load = view($templates, dispatch());
        echo view($templates, [ 'vars'=>['load'=>$load], 'use'=>'boot']);
    }

    main();