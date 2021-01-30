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
            // echo "<br> Массив до смещения: <br>";
            // var_dump($arr);
            // echo "<br> Массив после смещения: <br>";
            array_shift($arr);
            //$arr["pass"] = md5($arr["pass"]);
            //var_dump($arr);
            $ps = $pdo->prepare("INSERT INTO Customers(login, pass, roleId, imagepath, discount, total) VALUES(:login, :pass, :roleId, :imagepath, :discount, :total)");
            $ps->execute($arr);
            $this->id = $pdo->lastInsertId();
            //return 1062;
            // echo "Пользователь успешно добавлен в БД! <br>";
            // echo $this;
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
}