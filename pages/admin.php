<h3>Админ-панель</h3>
<?php
if (isset($_POST['additem'])) {
    ?>
    <form action="index.php?page=4" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="itemName">Название:</label>
            <input type="text" name="itemName" class="form-control">
        </div>
        <div class="form-group">
            <label for='catId'>Выберите категорию:</label>
            <select name='catId' class="form-control">
        </div>
        <?php

        $pdo=Tools::connect();
        $ps=$pdo->prepare("SELECT * FROM Categories WHERE id=?");
        $ps->execute(array($id));
 
    </form>

        }
        else
        {
            if(is_uploaded_file($_FILES["imagepath"]["tmp_name"])){
                $path="images/".$_FILES['imagepath']['name'];
                move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
            }
            //$itemName, $catId, $priceIn, $priceSale, $info, $rate, $action, $imagePath;

            $itemName=htmlspecialchars(trim($_POST["itemName"]));
            $catId=trim($_POST["catId"]);
            $priceIn=trim($_POST["priceIn"]);
            $priceSale=trim($_POST["priceSale"]);
            $info=trim($_POST["info"]);
            $catId=trim($_POST["catId"]);
            
            }
        }

