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

$pdo->exec($roles);
$pdo->exec($customers);
$pdo->exec($categories);
$pdo->exec($items);
$pdo->exec($images);
$pdo->exec($sales);
echo "<h3/><span style='color: green'>Таблицы созданы успешно!</span><h3/>";

$ps1=$pdo->prepare("INSERT INTO Roles(role) VALUES (:role)");
$ps1->execute(array("role"=>"Admin"));
$ps2=$pdo->prepare("INSERT INTO Roles (role) VALUES (:role)");
$role2 = "Customer";
$ps2->bindParam(":role", $role2);
$ps2->execute();
echo "<h3/><span style='color: green'>Роли созданы успешно!</span><h3/>";