<?php
require_once "include/include.php";

$myContact = null ;

if (isset($_GET["email"]) && !empty($_GET["activationKey"]) &&  !empty($_GET["email"]) && isset($_GET["activationKey"]))
{
    Users::activateUser($_GET["activationKey"],$_GET["email"]);
    Users::nullActivation($_GET["email"]);
    echo "<script>window.location.href='http://localhost:8080/backend-web/phoneBook/login.php';</script>";
}

if (isset($_POST["cid"]) && !empty($_POST["cid"]))
{
    Contacts::deleteContact($_POST["cid"]);
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

<form id='deleteContactForm' action='#' method='post'>
<input id='delCon'  type='hidden' name='cid'>
</form>

<form id='showContactForm' action='getContact.php' method='post'>
<input id='showCon'  type='hidden' name='show'>
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
                                if (isset($_SESSION["userInfo"]))
                                {
                                    $user=Users::getUserById($_SESSION["userInfo"]["id"]);
                                    $userId=$_SESSION["userInfo"]["id"];
                                    if ($user->imageAdd=="" and !empty($user))
                                    {
                                        $imgSrc="images/user.png";
                                    }
                                    else
                                    {
                                        $imgSrc=$user->imageAdd;
                                    }
                                    echo "<img src='$imgSrc'   class='picture  mt-2 mb-1'/>
                                       <p class='icons h6 ml-3'>$user->userName</p>";
                                }
                                else
                                {
                                    $userId=0;
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
                                            <form action='#' method='post'>
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


                                    if (isset($_SESSION["userInfo"]))
                                    {
                                        $contacts=Contacts::getCountContacts($_SESSION["userInfo"]["id"],5,0);
                                        if ($contacts!=0)
                                        {
                                            foreach ($contacts as  $contact)
                                            {
                                                $gName=Contacts::getContactGroup($contact->gid);
                                                echo "<div class='contactBox mt-3 text-white d-flex flex-row-reverse justify-content-between'> 
                                                            <input type='hidden' name='token1' value='$contact->id'>
                                                            <p class='mr-3'>$contact->name</p>
                                                            <p class='ml-2'>
                                                            <a class='delCon icons mr-3' href='#' ><i class='fa fa-trash'></i></a>
                                                            <a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                            <a class='showCon icons' data-toggle='modal' data-target='#MyModal' href='#'><i class='fa fa-eye'></i></a></p>
                                                       </div>";
                                            }

                                        }
                                        else
                                        {
                                            echo "<h4 class='mx-auto text-white mt-5'>.شما مخاطبی اضافه نکرده اید</h4>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<a href='login.php' class='mx-auto text-white  h5 mt-5 text-center'>.لطفا به حساب کاربری خود وارد شوید</a>";
                                    }


echo '
                
               
                                   </div>';
                                    if (isset($_SESSION["userInfo"]["id"]) and  $contacts!=0)
                                    {
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


<div class='modal fade' id='MyModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true' style='padding: 0 0 0 0'>
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


//---------------------test




require_once "footer.php";












