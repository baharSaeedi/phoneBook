<?php


include "include/include.php";



$res = $db->query("select * from ".DB_CONTACTS." where 0") or die("Query failed");
while ($i < $db->num_fields($res))
{ $meta = $db->fetch_field($res);
    $fldnam=$meta->name;
    print_r($fldnam."<br>");
}