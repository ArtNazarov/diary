<a href="/testdb.php?action=display">к списку</a>
<br/>
<form action="/testdb.php?action=update" method="post">
    <input type="hidden" name="id" value="%id%"/>
    <label for="situation" >Ситуация</label><br/>
    <textarea id="situation" name="situation"  cols="80" rows="5">%situation%</textarea>
        <hr/>
    <label for="thoughts">Мысли</label><br/>
    <textarea id="thoughts" name="thoughts" cols="80" rows="5">%thoughts%</textarea>
        <hr/>
    <label for="alternative">Другая точка</label><br/>
    <textarea id="alternative"  name="alternative" cols="80" rows="5">%alternative%</textarea>
        <hr/>
    <label for="conclusion">Заключение</label><br/>
    <textarea id="conclusion" name="conclusion"  cols="80" rows="5">%alternative%</textarea>
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
    <input type="text" name="sleep_hours" value="%sleep_hours%"  />
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
         
         <script>
         document.addEventListener("DOMContentLoaded", function(event) { 
                 document.getElementById('emotion_level').selectedIndex=%emotion_level%;
    document.getElementById('anx_val').selectedIndex=%anx_val%;
     document.getElementById('dep_val').selectedIndex=%dep_val%;
 document.getElementById('vex_val').selectedIndex=%vex_val%;
 document.getElementById('man_val').selectedIndex=%man_val%;
});

         
      </script>   
      <hr/>  
     
     
     <label for="date">Дата</label><br/>
     <input type="date" name="date" value="%date%"/>
    <hr/>
    <input type="submit" value="Изменить">
</form>
<script></script>