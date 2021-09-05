<?php
require_once "include/include.php";

$queryInsert = null;
$queryExist = null;
$msgErr = false;
$msgSuccess = false;
$errors = 0;
$isActivationExist = null;


if (isset($_POST["submit"])) {


    if (!empty($_POST["firstName"]) and !empty($_POST["lastName"]) and !empty($_POST["userName"]) and !empty($_POST["repassword"]) and !empty($_POST["email"]) and !empty($_POST["pass"]) and filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) and strlen($_POST["pass"]) > 5 ) {
        if ($_POST["pass"] == $_POST["repassword"]) {
            $queryExist = Users::isUserExist($_POST["email"]);
            if ($queryExist) {

                $msgErr = true;
                $msg="ایمیل وارد شده تکراری است";


            } else {

                $currentTime = microtime(true);
                $token = md5($_POST["email"] . $currentTime);
                $activationKey = $token;

                //        ---------------------------handle user Picture
                if (isset($_FILES["userPic"]) and !empty($_FILES["userPic"]))
                {
                    $uploadDir=__DIR__ . "/images/";
                    $name= strval(rand(11,99)) . "_" . $_FILES["userPic"]["name"];
                    $thumbName= "thumb"  . $name;
                    $path="images/users/". $thumbName;
                    $locationFile=$uploadDir . $name ;
                    $allowedFileType=array("image/jpeg", "image/jpg");

                    if (in_array($_FILES["userPic"]["type"],$allowedFileType))
                    {
                        move_uploaded_file($_FILES["userPic"]["tmp_name"], $locationFile);

                        $objThumbImage = new ThumbImage($locationFile);
                        $objThumbImage->createThumb($path, 150);
                        $msgSuccess=true;
                        $queryInsert = Users::InsertUser($_POST["firstName"], $_POST["lastName"], $_POST["userName"], $_POST["email"], $_POST["pass"],  $activationKey , $path);

                    }
                    else
                    {
                        $msgErr=true;
                        $msg="فرمت فایل وارد شده درست نیست";
                    }
                }
                else
                {
                    $queryInsert = Users::InsertUser($_POST["firstName"], $_POST["lastName"], $_POST["userName"], $_POST["email"], $_POST["pass"],  $activationKey);
                }

                if ($queryInsert) {

                    $msgSuccess = true;
                    $msg="ایمیل فعالسازی برای شما ارسال شد";


                    $mail_subject = "لینک تایید ایمیل";
                    $mail_body = '
                         <section style="width: 40%;padding: 50px;margin: auto;background-color:#F2F2F2 ;box-shadow: 1px 1.5px 8px #b7b7b7;direction: rtl;font-family: Tahoma;border-radius: 2.5px;">
       <h1 style="color: silver;text-align: center;padding: 0;margin: 0;padding-bottom: 25px;font-weight: 100;">لینک فعالسازی حساب کاربری</h1>
       <hr color="silver" size="0.5" style="width: 70%">   
          <center><a href="http://localhost:8080/backend-web/phoneBook?activationKey=' . $activationKey . '&email=' . $_POST["email"] . '" style="display: inline-block;padding: 18px 20px;text-decoration: none;border: 1px solid;text-align: center;border-radius: 5px;color: #FFF;background-color: #494f54;font-size: 18px;border-right:2px solid #0b2e13;border-bottom: 5px solid #0b2e13;margin-top: 30px">فعالسازی حساب کاربری</a></center>
   <p style="text-align: center;color: tomato;margin: 25px 0;"><small>درصورت ارسال اشتباه ایمیل آنرا نادیده بگیرید</small></p></section>';
                    Users::sendMail($_POST["email"], $mail_subject, $mail_body);
                }
            }
        }
    } else {
        if (empty($_POST["firstName"])) {
            $errors++;
        }
        if (empty($_POST["lastName"])) {
            $errors++;
        }
        if (empty($_POST["userName"])) {
            $errors++;
        }
        if (empty($_POST["pass"])) {
            $errors++;
        }

        if (strlen($_POST["pass"]) <= 5) {
            $errors++;
        }

        if (empty($_POST["email"])) {
            $errors++;
        }
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors++;
        }

    }
}
?>



<?php
echo '<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>phoneBook</title>
    <link rel="stylesheet" href="fontawesome-free-5.13.0-web/css/all.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>'

?>


<?php

echo '<section id="phoneBook">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-10 ">
            <div class="container">
                <div class="row align-items-center ">
                        <div class="mainBoxCard">

                            <div class="boxBody col-12">
                                <form id="signUp" action="#" method="post" class="text-light" enctype="multipart/form-data">

                                    <fieldset>
                                  
                                  
                                  <div class="form-group text-center mt-5">
                                <label for="pic" class="labelPic text-center"><i class="fa fa-camera"></i><img src="" alt="" id="imageVisitor" class="d-none" /></label>
                              
                                <input type="file" id="pic" name="userPic" class="form-control-file d-none text-right" >
                            </div>
                                
                                    
                                    
                                        <div class="form-group text-right ">
                                            <label for="" class="">نام</label>
                                            <input type="text" name="firstName" class="form-control text-right " placeholder="نام" >
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">نام خانوادگی</label>
                                            <input type="text" name="lastName" class="form-control text-right" placeholder="نام خانوادگی">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">نام کاربری</label>
                                            <input type="text" name="userName" class="form-control text-right" placeholder="نام کاربری">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">ایمیل</label>
                                            <input type="text" name="email" class="form-control text-right" placeholder="ایمیل">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">رمزعبور</label>
                                            <input type="password" name="pass" class="form-control text-right" placeholder="رمزعبور">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">تکرار رمزعبور</label>
                                            <input type="password" name="repassword" class="form-control text-right" placeholder="رمزعبور">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-center">
                                        <button class="btn btn-cancel"><a href="http://localhost:8080/backend-web/phoneBook"> انصراف  </a></button>
                                            <input type="submit"  data-add="addUser" class="btn btn-submit" name="submit" value="ثبت نام" >
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
</section>








';

require_once "footer.php";
 if($msgSuccess) : ?>
    <script>
        swal({
            title:"<?php echo $msg ?>",
            icon:"success" ,
            button:"بستن",
            timer:3000
        }) .then( function () {
            window.location = "http://localhost/phoneBook";
        })
    </script>
<?php endif; ?>







