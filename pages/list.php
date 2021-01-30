<?php
include_once("classes.php");
$catId=$_POST["catId"];
$items=null;
if($catId!=0)
$items=Item::getItems($catId);
else
$items=Item::getItems();
foreach($items as $item)
{
    echo "<div>" . $item->itemName ."</div>";
}

// $str=json_encode($items);
// echo $str;