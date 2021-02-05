<?php
if (isset($_POST['logbtn'])) {
    if (Tools::authorization($_POST['login'], $_POST['passw'])) {
        echo "<script>";
        echo "window.location = 'index.php?page=1'";
        echo "</script>";
    }
} else {
?>
<div class="container mt-4">
	<div class="row">
		<div class="col">
            <form action="index.php?page=6" method="POST" enctype="multipart/form-data">
                <h2 >Авторизоваться</h2>
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" class="form-control" placeholder="Введите логин" required>
                </div>
                <div class="form-group">
                    <label for="passw">Password</label>
                    <input type="password" id="passw" name="passw" class="form-control" placeholder="Введите пароль" required>
                </div>
                <button type='submit' name='logbtn' class="btn btn-primary">Авторизоваться</button>
            </form>
            </div>
		</div>
	</div>
<?php } ?>