<?php
require_once "include/include.php";

$msgErr = false;
$msgSuccess = false;
$errors = 0;
$infoTypes=Contacts::infoTypes();
$groups=Contacts::getGroups();

//-------------------------edit process-------------------------------------------
if (isset($_POST["editUser"])) {


    if (!empty($_POST["editId"]) && !empty($_POST["name"]) ) {
        $gid=Contacts::handleGroup($_POST["group"]);

        //        ---------------------------handle user Picture
        if (isset($_FILES["userPic"]) and !empty($_FILES["userPic"]))
        {
            if (!empty($_FILES["userPic"]["name"]))
            {
                $uploadDir=__DIR__ . "/images/";
                $name= strval(rand(11,99)) . "_" . $_FILES["userPic"]["name"];
                $thumbName= "thumb"  . $name;
                $path="images/contacts/". $thumbName;
                $locationFile=$uploadDir . $name ;
                $allowedFileType=array("image/jpeg", "image/jpg" , "image/JPG" , "image/JPG 2000" , "image/PNG"  );


                if (in_array($_FILES["userPic"]["type"],$allowedFileType))
                {
                    move_uploaded_file($_FILES["userPic"]["tmp_name"], $locationFile);

                    $objThumbImage = new ThumbImage($locationFile);
                    $objThumbImage->createThumb($path, 150);
                    $msgSuccess=true;
                    Contacts::editContact($_POST["editId"],$_POST["name"],$gid,$path);

                }
                else
                {
                    $msgErr=true;
                    $msg="فرمت فایل وارد شده درست نیست";
                }
            }
            else
            {
                $imageAdd=Contacts::getImageAdd($_POST["editId"]);
                Contacts::editContact($_POST["editId"],$_POST["name"],$gid,$imageAdd);
            }
        }
        else
        {
            $imageAdd=Contacts::getImageAdd($_POST["editId"]);
            Contacts::editContact($_POST["editId"],$_POST["name"],$gid,$imageAdd);
        }


        if (isset($_POST["deleteInfo"]) and !empty($_POST["deleteInfo"]))
        {
            $deletes=$_POST["deleteInfo"];
            foreach ($deletes as $delete)
            {
                Contacts::deleteInfo($delete);
            }
        }

        if (isset($_POST["phone"]) and !empty($_POST["phone"]))
        {
            $phones=$_POST["phone"];
            $types=$_POST["phoneType"];
            $phoneIds=$_POST["phoneId"];
            foreach ($phones as $phone)
            {
                $index=array_search($phone,$phones);
                $pid=Contacts::GetPid($types[$index]);
                $infoId=$phoneIds[$index];
                if (!empty($phone))
                {
                    if (!empty($infoId))
                    {
                        Contacts::editInfo($infoId,$phone,$pid);
                        $msgSuccess=true;
                    }
                    else
                    {
                        Contacts::InsertInfo($_POST["editId"],1,$phone,$pid);
                        $msgSuccess=true;
                    }

                }
            }
        }

        foreach ($infoTypes as $infoType) {
            if (isset($_POST[$infoType["id"]]) and !empty($_POST[$infoType["id"]])) {
                if ($infoType!=1)
                {
                    $infos = $_POST[$infoType["id"]];
                    $Ids=$_POST[$infoType["id"]."Id"];

                    foreach ($infos as $info) {
                        $index=array_search($info,$infos);
                        $infoId=$Ids[$index];
                        if (!empty($info)) {
                            if (!empty($infoId))
                            {
                                Contacts::editInfo($infoId,$info);
                                $msgSuccess=true;
                            }
                            else
                            {
                                Contacts::InsertInfo($_POST["editId"],$infoType["id"],$info);
                                $msgSuccess=true;
                            }

                        }
                    }
                }
            }
        }

    }
}

//------------------------show contact information---------------------------------------------

$phoneStr="";
$infoStr=array(
    "1" => "",
    "2" => "",
    "3" => "",
);


if (isset($_SESSION["userInfo"]) && isset($_POST["editId"]) && !empty($_POST["editId"]))
{
    $myContact=Contacts::getContactById($_SESSION["userInfo"]["id"],$_POST["editId"]);
}


$infos=$myContact->info;
if (is_array($infos))
{
    foreach ($infos as $info)
    {
        if ($info["type"]==1)
        {

            $pType=Contacts::getPhoneType($info["phoneTypeId"]);
            $phoneStr .= "
<div class='form-group d-flex justify-content-between text-right'>
                                                    <p class='delInfo icons mr-3 mt-4'  ><i class='fa fa-trash'></i></p> 
                                                    <input type='hidden' name='' value='".$info["infoId"]."'>
                                                    <input type='hidden' name='phoneId[]' value='".$info["infoId"]."'>
                                                     <input list='phones' name='phoneType[]'  class='form-control text-center mr-2 w-25' value= '$pType' >
  <datalist id='phones' >
    <option value= 'تلفن همراه'>
    <option value= 'خانه'>
    <option value= 'محل کار'>
    <option value= 'فکس'>
  </datalist>
                                                    <input type='tel' name='phone[]' class='form-control text-right' value='".$info["info"]."'>
                                                    </div>
                    ";

        }
        else
        {

            $Type=Contacts::GetType($info["type"]);
            $infoStr[$info["type"]] .= "
<div class='form-group d-flex justify-content-between text-right'>
                                                    <p class='delInfo icons mr-3 mt-4'  ><i class='fa fa-trash'></i></p> 
                                                    <input type='hidden' name='' value='".$info["infoId"]."'>
                                                    <input type='hidden' name='".$info["type"]."Id[]' value='".$info["infoId"]."'>
                                                    <input type='text' name='".$info["type"]."[]' class='form-control text-right' value='".$info["info"]."'>
                                                    </div>
                    ";
        }
    }
}




if (isset($_POST["infoId"]) && !empty($_POST["infoId"]))
{
    Contacts::deleteInfo($_POST["infoId"]);
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





<form id='deleteContactForm' action='#' method='post'>
<input id='delCon'  type='hidden' name='infoId'>
</form>


<section id='phoneBook'>
<div class='container'>
    <div class='row justify-content-center'>
        <div class='col-10 '>
            <div class='container'>
                <div class='row align-items-center '>

                    <div class='parent position-relative'>

                        <div class='mainBoxCard position-absolute '>

                            <div class='boxBody col-12'>
                              
                                    <div class='contactsBox text-center'>
                                        <form id='editContact' action='#' method='post' class='text-light' enctype='multipart/form-data'>
                                        
                                         ";




        $gName=Contacts::getContactGroup($myContact->gid);
        if (isset($_SESSION["userInfo"]))
        {
            if (!empty($myContact))
            {
                if (is_null($myContact->imageAdd) )
                {
                    echo " <div class='form-group text-center mt-5'>
                                <label for='pic' class='labelPic text-center'><i class='fa fa-camera'></i><img src='' alt='' id='imageVisitor' class='d-none' /></label>
                              
                                <input type='file' id='pic' name='userPic' class='form-control-file d-none text-right' >
                            </div>
                           ";
                }
                else
                {
                    echo  "
 <img src='$myContact->imageAdd' alt='' id='imageVisitor'  class='mt-5'/>
 <div class='form-group text-center '>
                                <label for='pic' class=' text-center '><i class='fa fa-edit icons'></i></label>
                                <input type='file' id='pic' name='userPic' class='form-control-file d-none text-right'>
                            </div>
                           
                ";
                }

                echo "
                                                          
                                                            <input type='hidden' name='editId' value='$myContact->id'>
                                                            <input name='name' class='mr-2 form-control form-edit  h1 mx-auto text-white text-center w-25' value='".$myContact->name."'>
                                                            <p class='ml-2'>
                                                           
                                                           
                                              ";

                echo "
                <div  class='phoneBox inputBox  text-right'>
                                                <div class='inputs'>
                                                 <label for='' class='text-right'> شماره تلفن </label>
                                                   $phoneStr
                                                </div>
                                            <p  class='morePhone  text-white mr-3 mt-2 '>اضافه کردن شماره تلفن<i class='ml-1 icons mt-5 fa fa-plus '></i></p>
                                        </div>
                ";


                foreach ($infoTypes as $infoType)
                {

                    if ($infoType["id"]!=1)
                    {
                        echo "
                                                <div  class='phoneBox inputBox text-right mt-5'>
                                                    <label for='' class='text-right'> ".$infoType["infoName"]." </label>
                                                        <div class='inputs'> ";
                                                         if (!empty($infoStr[$infoType["id"]]))
                                                         {
                                                             echo $infoStr[$infoType["id"]];
                                                         }
                                                         else
                                                         {
                                                            echo "<div class='form-group d-flex justify-content-between text-right'>
                                                    <p class='delInput icons mr-3 mt-4'  ><i class='fa fa-trash'></i></p>
                                                     <input type='hidden' name='".$infoType["id"]."Id[]' value=''>
                                                    <input type='text' name='".$infoType["id"]."[]' class='form-control text-right' value=''>
                                                    </div>";
                                                         }
                                                     echo "  </div>
                                                    <p  class='moreInfo  text-white mr-3 mt-2 '>اضافه کردن ".$infoType["infoName"]."<i class='ml-1 icons mt-5 fa fa-plus '></i></p>
                                                </div>
                                                ";
                    }
                }


                $group=Contacts::getContactGroup($myContact->gid);
                if (!empty($group))
                {
                    echo "  
                                         <div class='form-group text-right'>
                                            <label for='' class=''>گروه</label>
                                            <input list='groups' type='text' name='group' class='form-control text-right' value='$group'>
                                              <datalist id='groups' >";
                    foreach ($groups as $gp)
                    {

                        echo "<option value= '".$gp["gName"]."'>";

                    }

                    echo "     
                                  </datalist>             
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
                 <div class="form-group text-center mt-5">
                        <a class="btn btn-sign " href="http://localhost:8080/backend-web/phoneBook">انصراف</a>
                         <input type="submit"   class="btn  btn-submit " data-editUser="edit" name="editUser" value="ثبت تغییرات" >
                </div>
                                        
                                      </form>
                                   </div>
                                </div>
                            </div>
                        </div>
 

                                
       ';
        


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
</section>


';






require_once "footer.php";


 if($msgSuccess) : ?>
    <script>
        swal({
            title:"تغییرات ثبت شد",
            text:'',
            icon:"success" ,
            button:"بستن",
            timer:3000
        }) .then( function () {
           window.location = "http://localhost/phoneBook";
        })
    </script>
<?php endif;



