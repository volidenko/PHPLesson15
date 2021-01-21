<?php
include_once("pages/classes.php");
$catId=$_POST["catId"];
$items=null;
if($catId!=0)
$items=Item::getItems($catId);
else
$items=Item::getItems();
foreach($items as $item)
{
echo $item->$itemName;
}

// $str=json_encode($items);
// echo $str;