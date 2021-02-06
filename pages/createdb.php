<?
include_once("classes.php");
$pdo=Tools::connect();

$roles = "CREATE TABLE Roles(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    role varchar(32) not null UNIQUE
    ) default charset='utf8'";

$customers = "CREATE TABLE Customers(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    login varchar(32) not null UNIQUE,
    pass varchar(128), 
    roleId int,
    FOREIGN KEY (roleId) REFERENCES Roles(id) ON UPDATE CASCADE,
    imagepath varchar(256),
    discount int default 0,
    total double
) default charset='utf8'";

$categories = "CREATE TABLE Categories(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    category varchar(32) not null UNIQUE
) default charset='utf8'";

$items = "CREATE TABLE Items(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    itemName varchar(64) not null,
    catId int,
    FOREIGN key (catId) REFERENCES Categories(id) ON UPDATE CASCADE,
    priceIn double,
    priceSale double,
    info varchar(256),
    imagePath varchar(256),
    rate double,
    action int
) default charset='utf8'";

$images = "CREATE TABLE Images(
    id int not null PRIMARY KEY AUTO_INCREMENT, 
    itemId int,
    FOREIGN key (itemId) REFERENCES Items(id) ON DELETE CASCADE,
    imagepath varchar(256)
) default charset='utf8'";

$sales = "CREATE TABLE Sales(
    id int not null PRIMARY KEY AUTO_INCREMENT, 
    itemId int,
    FOREIGN key (itemId) REFERENCES Items(id) ON UPDATE CASCADE,
    customerId int,
        FOREIGN key (customerId) REFERENCES Customers(id) ON UPDATE CASCADE,
	quantity int,
    date date
) default charset='utf8'";

// $pdo->exec($roles);
// $pdo->exec($customers);
// $pdo->exec($categories);
// $pdo->exec($items);
// $pdo->exec($images);
// $pdo->exec($sales);
// echo "<h3/><span style='color: green'>Таблицы созданы успешно!</span><h3/>";

// $ps1=$pdo->prepare("INSERT INTO Roles(role) VALUES (:role)");
// $ps1->execute(array("role"=>"Admin"));
// $ps2=$pdo->prepare("INSERT INTO Roles (role) VALUES (:role)");
// $role2 = "Customer";
// $ps2->bindParam(":role", $role2);
// $ps2->execute();
// echo "<h3/><span style='color: green'>Роли созданы успешно!</span><h3/>";

// $cat1 = new Category('Ноутбуки');
// $cat2 = new Category('Телефоны');
// $cat3 = new Category('Компьютеры');
// $cat1->intoDb();
// $cat2->intoDb();
// $cat3->intoDb();
// echo "<h3/><span style='color: green'>Категории созданы успешно!</span><h3/>";

//$item1 = new Item('Samsung Galaxy M21', '2', '5499', '5999', 'Широкий 6.4-дюймовый безграничный U-дисплей, которым оснащен Galaxy M21, воспроизводит для вас еще больше любимого контента.', 'images/samsung_sm_m215fzkusek_images_17618015719.jpg');
// $item2 = new Item('Xiaomi Redmi Note 9 Pro', '2', '6499', '6999', 'Почувствуйте на собственном опыте, что значит новый уровень мобильной фотографии, и делайте профессиональные снимки одним нажатием.', 'images/xiaomi_redmi_note_9_pro_6_64gb_tropical_green_images_18071028649.jpg');
// $item3 = new Item('Huawei P40 Pro 8/256GB Silver Frost Slim Box', '2', '22999', '23999', 'Выразите себя с помощью ультракамеры Leica с четырьмя объективами. Делайте фото и снимайте видео где угодно и когда угодно.', 'images/huawei_p40_pro_8_256gb_silver_slim_box.jpg');
// $item4 = new Item('Apple iPhone 12 64GB Blue', '2', '28999', '29999', 'Великолепный яркий дисплей Super Retina XDR 6,1 дюйма. Передняя панель Ceramic Shield, с которой риск повреждений дисплея при падении в 4 раза ниже.', 'images/apple_iphone_12_64gb_blue.jpg');
$item5 = new Item('Acer Aspire 5 A515-55G', '1', '16999', '18999', 'Aspire 5 компактный ноутбук в тонком корпусе с металлической крышкой, качественным Full HD IPS дисплеем и богатым набором интерфейсов. Благодаря производительным компонентам, ноутбук прекрасно справится с ресурсоемкими задачами.', 'images/Acer Aspire 5 A515-55G.jpg');
//$item1->intoDb();
// $item2->intoDb();
// $item3->intoDb();
// $item4->intoDb();
$item5->intoDb();

echo "<h3/><span style='color: green'>Товары созданы успешно!</span><h3/>";