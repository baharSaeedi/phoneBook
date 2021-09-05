
<?php
require_once "include/include.php";

$msgErr = false;
$msgSuccess = false;
$errors = 0;




$infoTypes=Contacts::infoTypes();
$groups=Contacts::getGroups();

if (isset($_POST["insertUser"])) {


    if (isset($_POST["name"]) and !empty($_POST["name"])  ) {

        //        ---------------------------handle contact Picture
        if (isset($_FILES["userPic"]) and !empty($_FILES["userPic"]))
        {
            $uploadDir=__DIR__ . "/images/";
            $name= strval(rand(11,99)) . "_" . $_FILES["userPic"]["name"];
            $thumbName= "thumb"  . $name;
            $path="images/contacts/". $thumbName;
            $locationFile=$uploadDir . $name ;
            $allowedFileType=array("image/jpeg", "image/jpg");

            if (in_array($_FILES["userPic"]["type"],$allowedFileType))
            {
                move_uploaded_file($_FILES["userPic"]["tmp_name"], $locationFile);

                $objThumbImage = new ThumbImage($locationFile);
                $objThumbImage->createThumb($path, 150);
            }
            else
            {
                $msgErr=true;
                $msg="فرمت فایل وارد شده درست نیست";
            }
        }
        else
        {
            $path=null;
        }


            if (isset($_POST["group"]))
            {
                $gid=Contacts::handleGroup($_POST["group"]);
                $cid=Contacts::InsertContact($_SESSION["userInfo"]["id"],$_POST["name"],$gid,$path);
                $msgSuccess=true;
            }
            else
            {
                $cid=Contacts::InsertContact($_SESSION["userInfo"]["id"],$_POST["name"],1,$path);
                $msgSuccess=true;
            }



        foreach ($infoTypes as $infoType)
        {
            if (isset($_POST[$infoType["id"]]) and !empty($_POST[$infoType["id"]]))
            {
                $infos=$_POST[$infoType["id"]];
                foreach ($infos as $info)
                {
                    if (!empty($info))
                    {
                        Contacts::InsertInfo($cid,$infoType["id"],$info);
                        $msgSuccess=true;
                    }
                }
            }
        }

        if (isset($_POST["phone"]) and !empty($_POST["phone"]))
        {
            $phones=$_POST["phone"];
            $types=$_POST["phoneType"];
            foreach ($phones as $phone)
            {
                $index=array_search($phone,$phones);
                $pid=Contacts::GetPid($types[$index]);
                if (!empty($phone))
                {
                    Contacts::InsertInfo($cid,1,$phone,$pid);
                    $msgSuccess=true;
                }
            }
        }

    } else {
        if (empty($_POST["name"])) {
            $errors++;
        }
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

<section id='phoneBook'>
<div class='container'>
    <div class='row justify-content-center'>
        <div class='col-10 '>
            <div class='container'>
                <div class='row align-items-center '>

                    <div class='parent position-relative'>

                        <div class='mainBoxCard position-absolute '>


                            <div class='boxHeader d-flex justify-content-between col-12 h-25'>

                            </div>

                            <div class='boxBody col-12'>
                                    <div class='col-12'>
                                    
                                     <form autocomplete='off'  id='addContact' action='#' method='post' class='text-light text-right'  enctype='multipart/form-data'>

                                    <fieldset>
                                  
                                    
                                  <div class='form-group text-center mt-5'>
                                    <label for='pic' class='labelPic text-center'><i class='fa fa-camera'></i><img src='' alt='' id='imageVisitor' class='d-none' /></label>
                                <input type='file' id='pic' name='userPic' class='form-control-file d-none text-right' >
                            </div>  
                                    
                                    
                                        <div class='form-group text-right'>
                                            <label for='' class=''>نام</label>
                                            <input type='text' name='name' class='form-control text-right'>
                                            <small class='err'></small>
                                        </div>

                                        <div  class='phoneBox inputBox text-right'>
                                                <div class='inputs'>
                                                 <label for='' class='text-right'> شماره تلفن </label>
                                                    <div class='form-group d-flex justify-content-between text-right'>
                                                    <p class='delInput icons mr-3 mt-4'  ><i class='fa fa-trash'></i></p> 
                                                     <input list='phones' name='phoneType[]'  class='form-control text-center mr-2 w-25' value= 'تلفن همراه' >
  <datalist id='phones' >
    <option value= 'تلفن همراه'>
    <option value= 'خانه'>
    <option value= 'محل کار'>
    <option value= 'فکس'>
  </datalist>
                                                    <input type='tel' name='phone[]' class='form-control text-right'>
                                                    </div>
                                                </div>
                                            <p  class='morePhone  text-white mr-3 mt-2 '>اضافه کردن شماره تلفن<i class='ml-1 icons mt-5 fa fa-plus '></i></p>
                                        </div>
                                        
                                        
                                  ";
                                foreach ($infoTypes as $infoType)
                                    {

                                        if ($infoType["infoName"]!="phoneNumber")
                                            {
                                                echo "
                                                <div  class='phoneBox inputBox text-right mt-5'>
                                                    <label for='' class='text-right'> ".$infoType["infoName"]." </label>
                                                        <div class='inputs'>
                                                         
                                                            <div class='form-group d-flex justify-content-between text-right'>
                                                            <p class='delInput icons mr-3 mt-4' ><i class='fa fa-trash'></i></p> 
                                                             
                                                            <input type='text' name='".$infoType["id"]."[]' class='form-control text-right'>
                                                            </div>
                                                        </div>
                                                    <p  class='moreInfo  text-white mr-3 mt-2 '>اضافه کردن ".$infoType["infoName"]."<i class='ml-1 icons mt-5 fa fa-plus '></i></p>
                                                </div>
                                                ";
                                            }
                                    }
                                      echo "  
                                         <div class='form-group text-right'>
                                            <label for='' class=''>گروه</label>
                                            <input list='groups' type='text' name='group' class='form-control text-right' >
                                              <datalist id='groups' >";
                                foreach ($groups as $group)
                                    {

                                        echo "<option value= '".$group["gName"]."'>";

                                    }

                          echo "     
                                  </datalist>             
                                        </div>
                                        
                                       
                           


                                        <div class='form-group text-center'>
                                        <a class='btn btn-sign ' href='http://localhost:8080/backend-web/phoneBook'>انصراف</a>
                                            <input type='submit'  data-Insert='addUser' class='btn btn-submit ' name='insertUser' value='ثبت تغییرات' >
                                        </div>







                                    </fieldset>

                                </form>
           

                
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


";






require_once "footer.php";
if($msgSuccess) : ?>
    <script>
        swal({
            title:"کاربر مورد نظر ثبت شد",
            icon:"success" ,
            button:"بستن",
            timer:3000
        }) .then( function () {
            window.location = "http://localhost/phoneBook";
        })
    </script>
<?php endif; ?>
