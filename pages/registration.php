<h3>Регистрация</h3>
<?php
if(!isset($_POST["regbtn"])){
    ?>
    <form action="index.php?page=3" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="login">Логин:</label>
            <input type="text" name="login" class="form-control">
        </div>
        <div class="form-group">
            <label for='passw1'>Пароль:</label>
            <input type='password' name='passw1' class="form-control">
        </div>
        <div class="form-group">
            <label for='passw2'>Подтвердите пароль:</label>
            <input type='password' name='passw2' class="form-control">
        </div>
        <div class="form-group">
            <label for='imagepath'>Выберите аватар:</label>
            <input type='file' name='imagepath' class="form-control">
        </div>
        <button type='submit' name='regbtn' class="btn btn-primary">Зарегестрироваться</button>
    </form>
    <?php
}
else
{
    if(is_uploaded_file($_FILES["imagepath"]["tmp_name"])){
        $path="images/".$_FILES['imagepath']['name'];
        move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
        $login=trim($_POST["login"]);
        $passw1=trim($_POST["passw1"]);
        if(Tools::register($login, $passw1, $path)){
            echo "<h3><span style='color:green'>Пользователь ".$login." успешно добавлен!</span></h3>";
        }
    }
}
?>