<?php
// error_reporting(E_ALL);
// ini_set('display_errors', true);
error_reporting(0);

require_once "include/include.php";



// require_once "lib/config.ini.php";

$db = new phpdbform_db(DB_NAME,HOST_NAME,DB_USER,DB_PASS);
$db -> connect();
if (!$db) Die ("Database ".$_SESSION['DBName_acc']." does not exist");
//echo '<script language="javascript" src="functions.js"></script>';

//    include_once("htmltags.php");
//    include_once("function.php");
/*    $DateE=date("Y.m.d");
    $d=substr($DateE,8,2);
    $m=substr($DateE,5,2);
    $y=substr($DateE,0,4);
    $DateKind=$db->get_from_where("org_calendar","org","dbn='$dbn' and org_code='$acc_org_id'");
    if ($DateKind==2)
    {$yyyy=$y;
        $yy=substr($y,2,2);
        $mm=$m;
        $dd=$d;
    }
    else
    {$DateF=gregorian_to_jalali($y,$m,$d);
        $yyyy=$DateF[0];
        $yy=Substr($DateF[0],2,2);
        $mm=$DateF[1];
        $dd=$DateF[2];
    }
    $yymmdd=$yy.$mm.$dd;
    $yyyymmdd=$yyyy.$mm.$dd;
    $_SESSION["yyyymmdd"]=$yyyymmdd;*/


if (isset($right_click)) echo $right_click;

?>
