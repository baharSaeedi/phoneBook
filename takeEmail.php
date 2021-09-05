<?php
//if (isset($_COOKIE["userRemember"]))
require_once "include/include.php";
$queryLogIn=null;
$query = null ;
$msgErr = false;
$msgSuccess = false;


$msgErr=null;
$msg=null;
if (isset($_POST["submit"]))
{
    if (isset($_POST["email"]) && !empty($_POST["email"]))
    {
        $currentTime =  microtime(true);
        $token = md5($_POST["email"] . $currentTime);
        $resetKey = $token ;


        $msgErr=Users::checkEmail($_POST["email"]);

        if ($msgErr==0)
        {
            $msgErr=true;
            $msg="کابری با ایمیل مورد نظر موجود نیست";
        }
        else
        {
            $msgSuccess=true;
            Users::setKeyChangePass($_POST["email"],$resetKey);
            $mail_subject = "تغییر رمز";
            $mail_body = '
                         <section style="width: 40%;padding: 50px;margin: auto;background-color:#F2F2F2 ;box-shadow: 1px 1.5px 8px #b7b7b7;direction: rtl;font-family: Tahoma;border-radius: 2.5px;">
       <h1 style="color: silver;text-align: center;padding: 0;margin: 0;padding-bottom: 25px;font-weight: 100;">تغییر رمز حساب کاربری</h1>
       <hr color="silver" size="0.5" style="width: 70%">   
          <center><a href="http://localhost:8080/backend-web/phoneBook/changePass.php?key='.$resetKey.'" style="display: inline-block;padding: 18px 20px;text-decoration: none;border: 1px solid;text-align: center;border-radius: 5px;color: #FFF;background-color: #494f54;font-size: 18px;border-right:2px solid #0b2e13;border-bottom: 5px solid #0b2e13;margin-top: 30px">تغییر رمز حساب کاربری</a></center>
   <p style="text-align: center;color: tomato;margin: 25px 0;"><small>درصورت ارسال اشتباه ایمیل آنرا نادیده بگیرید</small></p></section>';
            Users::sendMail($_POST["email"],$mail_subject,$mail_body);
            $msg="ایمیل تایید با موفقیت ارسال شد";
        }
    }
    else
    {
        $msg="ایمیل نمی تواند خالی باشد";
    }
}


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
<body>

<section id="phoneBook">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 ">
                <div class="container">
                    <div class="row align-items-center ">
                        <div class="mainBoxCard">

                            <div class="menubox col-12">

                            </div>



                            <div class="boxHeader  row justify-content-between h-25">
                                <i class="fa fa-user-circle icons mt-3 ml-4" style="font-size: 70px"></i>
                            </div>



                            <div class="boxBody col-12 pt-5">
                                <form id="loginUser" action="#" method="post" class="text-light mt-5">

                                    <fieldset>

                                        <div class="form-group text-right mt-3">
                                            <label for="" class="">ایمیل</label>
                                            <input type="email" name="email" class="form-control text-right" placeholder="ایمیل">
                                            <small class="err"></small>
                                        </div>
                                        
                                       


                                        <div class="form-group text-center ">
                                          <button class="btn btn-cancel"><a href="http://localhost:8080/backend-web/phoneBook">انصراف </a></button>
                                          
                                        <input type="submit"   class="btn btn-submit  " name="submit" value="دریافت ایمیل تایید" >   
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



if($msgErr) : ?>
    <script>
        swal({title:"خطا",text:'<?php echo $msg ?>',icon:"error" , button:"بستن",timer:3000})
    </script>

<?php endif; ?>

<?php  if($msgSuccess) : ?>
    <script>
        swal({
            title:"ایمیل با موفقیت ارسال شد",
            text:'<?php echo $msg ?>',
            icon:"success" ,
            button:"بستن",
            timer:3000
        })
    </script>
<?php endif; ?>