<?php
require_once "include/include.php";
$Mode = "";
$myContact = null;
$infoStr = array(
    "1" => "",
    "2" => "",
    "3" => "",
);
foreach ($_GET as $key => $value) ${$key} = $value;
foreach ($_POST as $key => $value) ${$key} = $value;
$FileName = "index.php";

//-------------activate user
if (isset($_GET["email"]) && !empty($_GET["activationKey"]) && !empty($_GET["email"]) && isset($_GET["activationKey"])) {
    Users::activateUser($_GET["activationKey"], $_GET["email"]);
    Users::nullActivation($_GET["email"]);
    echo "<script>window.location.href='http://localhost:8080/backend-web/phoneBook/login.php';</script>";
}

//------delete contact
if ($Mode=="delete")
{
    $res= $db->query("delete  from " . DB_INFO . " WHERE `cid` = " . $cid);
    $res= $db->query("delete  from " . DB_CONTACTS . " WHERE `id` = " . $cid);
}



//--------------show an contact
if ($Mode == "show") {
    $res = $db->query("select * from " . DB_CONTACTS . " WHERE `id` = " . $cid);
    $mycontact = $db->fetch_assoc($res);
    if (!empty($mycontact)) {
        if (!is_null($mycontact["imageAdd"])) {
            $str .= "
                   
                          <div class='d-flex text-center justify-content-center'>
                           <div class='contactPic  text-center ml-3 mt-3 '>
                                <img src='" . $mycontact["imageAdd"] . "'   class='picture mt-2'/>
                           </div>
</div>
                            <div>
                ";
        } else {
            $str .= "<div>
                                                            <p><i class='fa fa-user-circle text-center mx-auto mt-5 icons' style='font-size: 100px'></i></p>";
        }

        $str .= "<input type='hidden' name='token1' value='" . $mycontact["id"] . "'>
                                                            <p class='mr-2  h1 mx-auto text-white '>" . $mycontact["name"] . "</p>
                                                            <p class='ml-2'>
                                                            <a class='delCon icons mr-3' href='#'><i class='fa fa-trash'></i></a>
<a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                            <a class='fadeModal icons'  href='#'><i class='fa fa-eye-slash'></i></a></p>

</div>";


        $res = $db->query("select * from " . DB_INFO . " WHERE `cid` = " . $cid);
        $infos = $db->num_rows($res);

        if ($infos != 0) {
            while ($info = $db->fetch_assoc($res)) {
                if ($info["type"] == 1) {

                    $res1 = $db->query("select typeName from " . DB_PHONETYPE . " WHERE `id` = " . $info["phoneTypeId"]);
                    $pType = $db->fetch_assoc($res1);
                    $pType = $pType["typeName"];
                    $infoStr[$info["type"]] .= "
<div class='text-right  d-flex  justify-content-around align-items-center mb-1 infos'> 
<p class='text-white'> " . $info["info"] . "</p> 
<p class='text-muted'>" . $pType . "</p>
</div>
";
                } else {
                    $res2 = $db->query("select infoName from " . DB_TYPE . " WHERE `id` = " . $info["type"]);
                    $Type = $db->fetch_assoc($res2);
                    $Type = $Type["infoName"];
                    $infoStr[$info["type"]] .= "
<div class='text-right  d-flex justify-content-around mb-1 infos'> 
<p class='text-white'> " . $info["info"] . "</p> 
<p class='text-muted pr-3'>$Type</p>
 </div>
";
                }
            }
        }

        foreach ($infoStr as $string) {
            if (!empty($string)) {
                $str .= "<div class='text-right infoBox'> " . $string . "</div>";
            }
        }

        $res = $db->query("select gName from " . DB_GROUP . " WHERE `gid` = " . $mycontact["gid"]);
        $group = $db->fetch_assoc($res);
        if ($group == !false) {
            $str .= "
                   <div class='text-right  infoBox d-flex justify-content-around'> 
<p class='text-white'> $group</p> 
<p class='text-muted pr-3'>گروه</p>
 </div>
";
        }

    } else {
        $str .= "<h4 class='mx-auto text-white mt-5'>.شما مخاطبی اضافه نکرده اید</h4>";
    }
}


echo "
<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>phoneBook</title>
    <link rel='stylesheet' href='fontawesome-free-5.13.0-web/css/all.css'>
    <link rel='stylesheet' href='node_modules/bootstrap/dist/css/bootstrap.css'>
    <link rel='stylesheet' href='css/style.css'>
    <link rel='stylesheet' href='css/formStyle.css'>
</head>
<body>





<form id='editContactForm' action='editUser.php' method='post'>
<input id='editCon' type='hidden' name='editId'>
</form>


<form id='getContactForm' action='getContact.php' method='post'>
<input id='getCon'  type='hidden' name='moreCon' value='5'>
</form>  

<section id='phoneBook'>
<div class='container'>
    <div class='row justify-content-center'>
        <div class='col-10 '>
            <div class='container'>
                <div class='row align-items-center '>

                    <div class='parent position-relative'>

                        <div class='mainBoxCard  '>

                            <div class='menubox col-12'>

                            </div>



                            <div class='boxHeader d-flex justify-content-between col-12 h-25'>
                                
                                <div class='userIcon ml-3 mt-3 '>";
//------show user infomation
if (isset($_SESSION["userInfo"])) {
    $user = $db->query("select * from " . DB_USERS . " WHERE `id` = " . $_SESSION["userInfo"]["id"]);
    $user = $user->fetch_array();
    $userId = $_SESSION["userInfo"]["id"];
    if ($user["imageAdd"] == "" and !empty($user)) {
        $imgSrc = "images/user.png";
    } else {
        $imgSrc = $user["imageAdd"];
    }
    echo "<img src='$imgSrc'   class='picture  mt-2 mb-1'/>
                                       <p class='icons h6 ml-3'>" . $user["userName"] . "</p>";
} else {
    $userId = 0;
    echo "<i class='fa fa-user-circle icons mt-3 ml-2' style='font-size: 70px'></i>
                                           <p class='icons ml-3 mt-1 h6'>ناشناس</p>";
}
echo "
 </div>
                            </div>
                            <div class='boxBody col-12'>
                                    <div class='col-12'>
                                        <div class='d-flex justify-content-end'>
                                            <a href='insertContact.php'><i class='fa fa-plus mr-3 mt-3 icons'></i></a>
                                            <form action='#' method='post' id='searchForm'>
                                                 <div class='form-group'>
                                                       <input type='text' name='search' class='search form-control form-control-sm text-right search' placeholder='search' autocomplete='off'>
                                                       <input type='hidden' name='userId' value='$userId'>
                                                       <small class='small'></small>
                                                   </div>
                                            </form>
</form>
                                        </div>
                                    </div> 
                                   <div class='searchLive m-0 p-0'>
        
                                   </div>
                                    <div class='contactsBox text-center p-3'>";

//--------show user contacts
if (isset($_SESSION["userInfo"])) {
    $res = $db->query("select * from " . DB_CONTACTS . " WHERE `uid` = " . $_SESSION["userInfo"]["id"]);
    $contacts = $db->num_rows($res);

    if ($contacts != 0) {
        while ($contact = $db->fetch_assoc($res)) {

            echo '<div class="contactBox mt-3 text-white d-flex flex-row-reverse justify-content-between"> 
                                                            <input type="hidden" name="token1" value="' . $contact["id"] . '">
                                                            <p class="mr-3">' . $contact["name"] . '</p>
                                                            <p class="ml-2">
                                                            <a class="delCon icons mr-3"  data-direct="?Mode=delete&cid=' . $contact["id"] . '"><i class="fa fa-trash"></i></a>
                                                            <a class="editCon icons mr-3" href="#"><i class="fa fa-edit"></i></a>
                                                            <a class="showCon icons" ><i onclick="window.location=\'?Mode=show&cid=' . $contact["id"] . '\'" class="fa fa-eye"></i></a></p>
                                                       </div>';
        }

    } else {
        echo "<h4 class='mx-auto text-white mt-5'>.شما مخاطبی اضافه نکرده اید</h4>";
    }
} else {
    echo "<a href='login.php' class='mx-auto text-white  h5 mt-5 text-center'>.لطفا به حساب کاربری خود وارد شوید</a>";
}


echo '
                
               
                                   </div>';
if (isset($_SESSION["userInfo"]["id"]) and $contacts != 0) {
    echo '<p id="moreCon" class=" text-center"><i class="icons mt-5 fa fa-plus"></i></p>';
}

echo '
                                </div>
                            </div>
                        </div>



                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>




';


//
////-----------------------------------------my modal---------------------------------------
echo "


<div class='modal fade mt-5' id='MyModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='false' style='padding: 0 0 0 0'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>

            <div class='modal-body'>
                <div class='container'>
    <div class='row justify-content-center'>
        <div class='col-11 '>
            <div class='container'>
                <div class='row align-items-center '>

                    <div class='parent position-relative'>

                        <div class='modalBoxCard position-absolute '>
                            <div class='boxBody col-12'>
                                    <div class='contactsBox text-center'>";
echo $str;

echo '
                                   </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




';



require_once "footer.php";
//-----show contact in modal
if ($Mode=="show")
{
    echo "<script> $(document).ready(function() {
      $('#MyModal').css('display','block');
      $('#MyModal').animate({'opacity':'1'},'slow');
    }) </script>";
}
