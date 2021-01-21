<?php
include_once("classes.php");
// $customer = new Customer("Vovik2", "1111", "images/photo1.png");
// $errCode = $customer->intoDb();
// echo "<br>". $errCode;

$customer = Customer::FromDb(1);
echo $customer;