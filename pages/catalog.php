<h3>Каталог</h3>
<div class="form-group">
    <label for='catId'>Выберите категорию:</label>
    <select name='catId' onchange="getItems(this.value)">
<?php
$pdo=Tools::connect();
$ps=$pdo->prepare("SELECT * FROM Categories");
$ps->execute();



</div>