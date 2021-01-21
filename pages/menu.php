<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
        <a <?php echo ($page == 1) ? "class='nav-link active'" : "class='nav-link'" ?> href="index.php?page=1">Каталог</a>
    </li>
    <li class="nav-item">
        <a <?php echo ($page == 2) ? "class='nav-link active'" : "class='nav-link'" ?> href="index.php?page=2">Корзина</a>
    </li>
    <li class="nav-item">
        <a <?php echo ($page == 3) ? "class='nav-link active'" : "class='nav-link'" ?> href="index.php?page=3">Регистрация</a>
    </li>
    <li class="nav-item">
        <a <?php echo ($page == 4) ? "class='nav-link active'" : "class='nav-link'" ?> href="index.php?page=4">Админ-панель</a>
    </li>
</ul>