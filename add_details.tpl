Добавление на заданную дату
Месяц - %month%
Год - %year%
Дата - %date%
 

<a href="/">к списку</a>
<br/>
<form action="/testdb.php?action=save_details" method="post">
    
    <label for="situation" >Ситуация</label><br/>
    <textarea id="situation" name="situation"  cols="80" rows="5"></textarea>
        <hr/>
    <label for="thoughts">Мысли</label><br/>
    <textarea id="thoughts" name="thoughts" cols="80" rows="5"> </textarea>
        <hr/>
    <label for="alternative">Другая точка</label><br/>
    <textarea id="alternative"  name="alternative" cols="80" rows="5"></textarea>
        <hr/>
    <label for="conclusion">Заключение</label><br/>
    <textarea id="conclusion" name="conclusion"  cols="80" rows="5"> </textarea>
        <hr/>
    <label for="tress">Когнитивное искажение</label><br/>
    <select name="tress">
        <option value="Преувеличение">Преувеличение</option>
        <option value="Ошибка оракула">Ошибка оракула</option>
    </select>
    <hr/>
     <label for="emotion">Преобладающая эмоция</label><br/>
    <select name="emotion">
        <option value="Радость">Радость</option>
        <option value="Печаль">Печаль</option>
    </select>
        <hr/>
    <label for="emotion_level">Уровень</label><br/>
    <select name="emotion_level" id='emotion_level'>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
    </select>
     <hr/>
     
       <hr/>
     <h3>Описание самочувствия</h3>
            <hr/>
    <label for="sleep_hours">Длительность сна</label><br/>
    <input type="text" name="sleep_hours" value="8"  />
         <hr/> 
         
     <label for="dep_val">Выраженность депрессии</label><br/>
      <select name="dep_val" id="dep_val">
        <option value="0">0 ( нет )</option>
        <option value="1">1 ( легкая )</option>
        <option value="2">2 ( умеренная )</option>
		<option value="3">3 ( тяжелая )</option>
    </select>  
         
          <hr/> 
         
     <label for="man_val">Выраженность мании</label><br/>
      <select name="man_val" id="man_val">
        <option value="0">0 ( нет )</option>
        <option value="1">1 ( легкая )</option>
        <option value="2">2 ( умеренная )</option>
		<option value="3">3 ( тяжелая )</option>
    </select>  
     
          <hr/> 
         
     <label for="vex_val">Степень раздражительности</label><br/>
      <select name="vex_val" id="vex_val">
        <option value="0">0 ( нет )</option>
        <option value="1">1 ( легкая )</option>
        <option value="2">2 ( умеренная )</option>
		<option value="3">3 ( тяжелая )</option>
    </select>  
     <hr/>
      <label for="anx_val">Степень тревожности</label><br/>
      <select name="anx_val" id='anx_val'>
        <option value="0">0 ( нет )</option>
        <option value="1">1 ( легкая )</option>
        <option value="2">2 ( умеренная )</option>
		<option value="3">3 ( тяжелая )</option>
    </select>  
         
        
      <hr/>  
     
     
     <label for="date">Дата</label><br/>
     <input type="date" name="date" id="date"  />
     <script>
         var d = parseInt( '%date%'.split('-')[2] );
         var m = %month%;
         var y = %year%;
         
         var v =  '%year%-';
    
         if (m<10) { v += '0'; };
         v = v + String(m)+'-';
         if (d<10) { v += '0'; };
         v = v + String(d);
         console.log(v);
         document.getElementById('date').value = v;
         </script>
    <hr/>
    <input type="submit" value="Сохранить заметку">
</form>
<script></script>