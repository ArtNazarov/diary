<a href="/test/testdb.php?action=display">к списку</a>
<br/>
<form action="/test/testdb.php?action=save" method="post">
    <label for="situation" >Ситуация</label><br/>
    <textarea id="situation" name="situation"  cols="80" rows="5"></textarea>
        <hr/>
    <label for="thoughts">Мысли</label><br/>
    <textarea id="thoughts" name="thoughts" cols="80" rows="5"></textarea>
        <hr/>
    <label for="alternative">Другая точка</label><br/>
    <textarea id="alternative"  name="alternative" cols="80" rows="5"></textarea>
        <hr/>
    <label for="conclusion">Заключение</label><br/>
    <textarea id="conclusion" name="conclusion"  cols="80" rows="5"></textarea>
        <hr/>
    <label for="tress">Искажение</label><br/>
    <select name="tress">
        <option value="Преувеличение">Преувеличение</option>
        <option value="Ошибка оракула">Ошибка оракула</option>
    </select>
    <hr/>
     <label for="emotion">Эмоции</label><br/>
    <select name="emotion">
        <option value="Радость">Радость</option>
        <option value="Печаль">Печаль</option>
    </select>
        <hr/>
    <label for="emotion_level">Уровень</label><br/>
    <select name="emotion_level">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
    </select>
     <hr/>
     <label for="date">Дата</label><br/>
     <input type="date" name="date" />
    <hr/>
    <input type="submit" value="Добавить">
</form>
