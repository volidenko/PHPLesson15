<h3>Каталог</h3>
<div class="form-group">
    <label for='catId'>Выберите категорию:</label>
    <select name='catId' onchange="getItems(this.value)">
<?php
$pdo=Tools::connect();
$ps=$pdo->prepare("SELECT * FROM Categories");
$ps->execute();
while($row= $ps->fetch())
{
    echo "<option value='". $row["id"]."'>" . $row["category"] . "</option>";
}
?>
</select>
</div>
<div id='items'></div>
<script>
async function getItems(val){
    let formData = new FormData();
    formData.append("catId", val);
    let response = await fetch("pages/list.php", {method: "POST", body: formData});
    if(response.ok === true){
        let list = await response.text();
        //console.log(list);
        let items = document.getElementById("items");
        items.innerHTML = list;
    }
}
</script>