<?php
class Tools
{
    static function connect($host = "localhost:3306", $dbname = "ShopDb", $user = "root", $pasw = "root")
    {
        $cs = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";
        $option = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        );
        try {
            $pdo = new PDO($cs, $user, $pasw, $option);
            return $pdo;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    static function register($login, $pasw, $imagepath)
    {
        $customer = new Customer($login, $pasw, $imagepath);
        $err = $customer->intoDb();
        if ($err) {
            if ($err == 1062) {
                echo "<h3/><span style='color: red'>Пользователь с таким логином существует!</span><h3/>";
                return false;
            } else {
                echo "<h3/><span style='color: red'>Код ошибки: " . $err . "</span><h3/>";
                return false;
            }
            return true;
        }
    }

    static function authorization($login, $pasw)
    {
        $user = Customer::FromDb($login);
        if ($user && $user->pasw === md5($pasw)) {
            $_SESSION['login'] = $login;
            if ($user->roleId == 1) {
                $_SESSION['admin'] = $login;
            }
        } else {
            echo "<h3/><span style='color: red'>Неверный пароль!</span><h3/>";
            return false;
        }
        return true;
    }
}

class Customer
{
    public $id;
    public $login;
    public $pass;
    public $roleId;
    public $imagepath;
    public $discount;
    public $total;

    function __construct($login, $pass, $imagepath, $id = 0)
    {
        $this->login = $login;
        $this->pass = $pass;
        $this->imagepath = $imagepath;
        $this->id = $id;
        $this->roleId = 2;
        $this->discount = 0;
        $this->total = 0;
    }
    function __toString()
    {
        return "Id: " . $this->id . "; Login: " . $this->login . ", password: " . $this->pass . ", Path: " . $this->imagepath;
    }
    function intoDb()
    {
        try {
            $pdo = Tools::connect();
            $arr = (array)$this;
            array_shift($arr);
            $ps = $pdo->prepare("INSERT INTO Customers(login, pass, roleId, imagepath, discount, total) VALUES(:login, :pass, :roleId, :imagepath, :discount, :total)");
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
        } catch (PDOException $ex) {
            $err = $ex->getMessage();
            echo "Exception: " . $err . "<br>";
            if (substr($err, 0, strpos($err, ":")) == "SQLSTATE[23000]")
                return 1062;
            else return $err;
        }
    }

    static function FromDb($id)
    {
        $customer = null;
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Customers WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $customer = new Customer($row["login"], $row["pass"], $row["imagepath"], $row["id"]);
            $customer->total = $row["total"];
            $customer->discount = $row["discount"];
            $customer->roleId = $row["roleId"];
            return $customer;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    static function getAll()
    {
        try {
            $customers = [];
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Customers");
            $ps->execute();
            while ($row = $ps->fetch(PDO::FETCH_ASSOC)) {
                $customer = new Customer($row['login'], $row['pass'], $row['imagepath'], $row['id']);
                $customer->total = $row['total'];
                $customer->discount = $row['discount'];
                $customer->roleId = $row['roleId'];
                array_push($customers, $customer);
            }
            return $customers;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
}

class Category
{
    public $id;
    public $category;
    function __construct($category, $id = 0)
    {
        $this->category = $category;
        $this->id = $id;
    }

    function intoDb()
    {
        try {
            $pdo = Tools::connect();
            $arr = (array)$this;
            array_shift($arr);
            $ps = $pdo->prepare("INSERT INTO Categories(category) VALUES(:category)");
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function delete($categoriesId)
    {
        try {
            $pdo = Tools::connect();
            foreach ($categoriesId as $id) {
                $ps = $pdo->prepare("DELETE FROM Categories WHERE id=?");
                $ps->execute(array($id));
            }
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function FromDb($id)
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Categories WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $category = new Category($row['category'], $row['id']);
            return $category;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    static function getCategory()
    {
        try {
            $categories = [];
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Categories");
            $ps->execute();
            while ($row = $ps->fetch(PDO::FETCH_ASSOC)) {
                $category = new Category($row['category'], $row['id']);
                array_push($categories, $category);
            }
            return $categories;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
}

class Item
{
    public $id, $itemName, $catId, $priceIn, $priceSale, $info, $rate, $action, $imagePath;
    function __construct($itemName, $catId, $priceIn, $priceSale, $info, $imagePath, $rate = 0, $action = 0, $id = 0)
    {
        $this->id = $id;
        $this->itemName = $itemName;
        $this->catId = $catId;
        $this->priceIn = $priceIn;
        $this->priceSale = $priceSale;
        $this->info = $info;
        $this->rate = $rate;
        $this->imagePath = $imagePath;
        $this->action = $action;
    }
    function intoDb()
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("INSERT INTO Items (itemName, catId, priceIn, priceSale, info, imagePath, rate, action)
            VALUES (:itemName, :catId, :priceIn, :priceSale, :info, :imagePath, :rate, :action)");
            $arr = (array)$this;
            array_shift($arr);
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
        } catch (PDOException $ex) {
            $err = $ex->getMessage();
            echo "Exception: " . $err . "<br>";
            return $ex->getCode();
        }
    }

    static function delete($itemsId)
    {
        try {
            $pdo = Tools::connect();
            foreach ($itemsId as $id) {
                $ps = $pdo->prepare("DELETE FROM Items WHERE id=?");
                $ps->execute(array($id));
            }
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function FromDb($id)
    {
        $item = null;
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Items WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $item = new Item(
                $row['itemName'],
                $row['catId'],
                $row['priceIn'],
                $row['priceSale'],
                $row['info'],
                $row['imagePath'],
                $row['rate'],
                $row['action'],
                $row['id']
            );
            return $item;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    static function getItems($catId = 0)
    {
        try {
            $pdo = Tools::connect();
            $items = array();
            if ($catId == 0) {
                $ps = $pdo->prepare("SELECT * FROM Items");
                $ps->execute();
            } else {
                $ps = $pdo->prepare("SELECT * FROM Items WHERE catId=?");
                $ps->execute(array($catId));
            }
            while ($row = $ps->fetch()) {
                $item = new Item(
                    $row["itemName"],
                    $row["catId"],
                    $row["priceIn"],
                    $row["priceSale"],
                    $row["info"],
                    $row["imagePath"],
                    $row["rate"],
                    $row["action"],
                    $row["id"]
                );
                $items[] = $item;
            }
            return $items;
        } catch (PDOException $ex) {
            return false;
        }
    }

    function shopping()
    {
        if (isset($_SESSION['login'])) {
            $customer = Customer::FromDb($_SESSION['login']);
            $sale = new Sale($this->id, $customer->id);
            $sale->intoDb();
        } else {
            $sale = new Sale($this->id);
            $sale->intoDb();
        }
    }
}

class Sale
{
    public $id;
    public $itemId;
    public $customerId;
    public $quantity;
    public $date;
    function __construct($itemId, $customerId = 0, $quantity = 1, $id = 0)
    {
        $this->itemId = $itemId;
        $this->customerId = $customerId;
        $this->quantity = $quantity;
        $this->date = date("m.d.y");
        $this->id = $id;
    }

    function intoDb()
    {
        try {
            $this->date = date('Y-m-d', strtotime(str_replace('-', '/', $this->date)));
            $pdo = Tools::connect();
            $arr = (array)$this;
            array_shift($arr);
            $ps = $pdo->prepare("INSERT INTO Sales(itemId, customerId, quantity, date) VALUES(:itemId, :customerId, :quantity, :date)");
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function FromDb($id)
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Sales WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $sale = new Sale($row['itemId'], $row['customerId'], $row['quantity'], $row['date'], $row['id']);
            return $sale;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    static function getSale()
    {
        try {
            $sales = [];
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Sales");
            $ps->execute();
            while ($row = $ps->fetch(PDO::FETCH_ASSOC)) {
                $sale = new Sale($row['itemId'], $row['customerId'], $row['quantity'], $row['date'], $row['id']);
                array_push($sales, $sale);
            }
            return $sales;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
}

class Image
{
    public $id;
    public $itemId;
    public $imagepath;

    function __construct($itemId, $imagepath, $id = 0)
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->imagepath = $imagepath;
    }

    function intoDb()
    {
        try {
            $pdo = Tools::connect();
            $arr = (array)$this;
            array_shift($arr);
            $ps = $pdo->prepare("INSERT INTO Images(itemId,imagepath) VALUES (:itemId, :imagepath)");
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function delete($imagesId)
    {
        try {
            $pdo = Tools::connect();
            foreach ($imagesId as $id) {
                $ps = $pdo->prepare("DELETE FROM Images WHERE id=?");
                $ps->execute(array($id));
            }
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function FromDb($id)
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Images where id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $image = new Image($row['itemId'], $row['imagepath'], $row['id']);
            return $image;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function getImage()
    {
        try {
            $pdo = Tools::connect();
            $images = [];
            $ps = $pdo->prepare("SELECT * FROM Images");
            $ps->execute();
            while ($row = $ps->fetch(PDO::FETCH_ASSOC)) {
                $image = new Image($row['itemId'], $row['imagepath'], $row['id']);
                array_push($images, $image);
            }
            return $images;
        } catch (PDOException $ex) {
            return false;
        }
    }

    static function getImageByGoodId($itemId)
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM Images where itemId=?");
            $ps->execute(array($itemId));
            $row = $ps->fetch();
            $image = new Image($row['itemId'], $row['imagepath'], $row['id']);
            return $image;
        } catch (PDOException $ex) {
            return false;
        }
    }
}