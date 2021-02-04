<h3>Админ-панель</h3>
<?php
if (!isset($_POST['additem'])) {
    ?>
    <form action="index.php?page=4" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="itemName">Название:</label>
            <input type="text" name="itemName" class="form-control">
        </div>
        <div class="form-group">
            <label for='catId'>Выберите категорию:</label>
            <select name='catId' class="form-control">
            <?php
            $pdo=Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Categories");
            $ps->execute();
            while($row = $ps->fetch()){
                echo "<option value='". $row["id"]."'>" . $row["category"] . "</option>";
            }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="priceIn">Цена поставки:</label>
            <input type="text" name="priceIn" class="form-control">
            <label for="priceSale">Цена продажи:</label>
            <input type="text" name="priceSale" class="form-control">
        </div>
        <div class="form-group">
            <label for="imagepath">Описание товара:</label>
            <textarea name="info" class="form-control"></textarea>
        </div>
            <div class="form-group">
            <label for="imagepath">Выберите фото:</label>
            <input type="file" name="imagepath" class="form-control">
        </div>
        <input type="submit" value="Добавить" class="btn btn-primary" name="additem">
    </form>
    <?php

}
else
{
    if(is_uploaded_file($_FILES["imagepath"]["tmp_name"])){
        $path = "images/". $_FILES["imagepath"]["name"];
        move_uploaded_file($_FILES["imagepath"]["tmp_name"], $path);
    }
    $itemName = htmlspecialchars(trim($_POST["itemName"]));
    $catId =trim($_POST["catId"]);
    $priceIn =doubleval(trim($_POST["priceIn"]));
    $priceSale = doubleval(trim($_POST["priceSale"]));
    $info =trim($_POST["info"]);
    $item = new Item($itemName, $catId, $priceIn, $priceSale, $info, $path);
    $err = $item->intoDb();
    if($err)
    {
        echo "<h3/><span style='color: red'>Ошибка: ". $err. "</span><h3/>";
    }
    else
        echo "<h3/><span style='color: green'>Товар успешно добавлен!</span><h3/>";
}