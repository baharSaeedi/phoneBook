<?php

require_once "include/include.php";
$queryLogIn=null;
$query = null ;
$msgErr = false;
$msgSuccess = false;

if (isset($_POST["email"]) && isset($_POST["pass"]) && !empty($_POST["email"]) && !empty($_POST["pass"]))
{
    $active=Users::isUserAvailable($_POST["email"]);
    if ($active==! false)
    {
        $query=Users::isUserExist($_POST["email"]);
        if ($query)
        {
            $userStatus=Users::isUserAvailable($_POST["email"]);
            if ($userStatus==1)
            {
                if (isset($_POST["rememberMe"]))
                {
                    $queryLogIn=Users::LoginUser($_POST["email"],$_POST["pass"],"on");
                }
                else
                {
                    $queryLogIn=Users::LoginUser($_POST["email"],$_POST["pass"]);
                }


                if ($queryLogIn)
                {
                    $msgSuccess=true;
                    $msg = "خوش آمدید!";
                }
                else
                {
                    $msgErr=true;
                    $msg = "رمز عبور یا ایمیل وارد شده صحیح نیست";
                }
            }
            else
            {
                $msgErr=true;
                $msg = "لطفا اکانت خود را فعال کنید";
            }
        }
        else
        {
            $msgErr=true;
            $msg = "رمز عبور یا ایمیل وارد شده صحیح نیست";
        }
    }
    else
    {
        $msgErr=true;
        $msg="لطفا حساب کاربری خود را از طریق لینک ارسالی فعال کنید";
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
    <link rel="stylesheet" href="css/formStyle.css">
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



                            <div class="boxBody col-12">
                                <form id="loginUser" action="#" method="post" class="text-light">

                                    <fieldset>

                                        <div class="form-group text-right">
                                            <label for="" class="">ایمیل</label>
                                            <input type="email" name="email" class="form-control text-right" placeholder="ایمیل">
                                            <small class="err"></small>
                                        </div>

                                        <div class="form-group text-right">
                                            <label for="" class="">رمزعبور</label>
                                            <input type="password" name="pass" class="form-control text-right" placeholder="رمزعبور">
                                            <small class="err"></small>
                                        </div>
                                        
                                        <div class="form-group form-check text-right t" style="margin: 30px 0">
                                         <label class="form-check-label  mr-4" style="transform: translate(0,-3px)" for="exampleCheck1"><small class="text-right"> مرا به خاطربسپار</small></label>
                            <input name="rememberMe" type="checkbox" class="form-check-input" id="exampleCheck1">
                           
                             </div>


                                        <div class="form-group text-right ">
                                          <a href="http://localhost:8080/backend-web/phoneBook/takeEmail.php"><button class="btn btn-cancel">بازیابی رمز عبور </button></a>
                                           
                                            <button class="btn btn-sign"><a href="signUp">ثبت نام</a></button>
                                        <input type="submit"   class="btn btn-submit  " name="submit" value="ورود" >   
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
        swal({title:"خطا",text:'<?php echo $msg ?>',icon:"error" , button:"بستن",timer:3000}).then(function () {
            window.location=window.location.pathname
        })
    </script>

<?php endif; ?>

<?php  if($msgSuccess) : ?>
    <script>
        swal({
            title:"با موفقیت وارد شدید",
            text:'<?php echo $msg ?>',
            icon:"success" ,
            button:"بستن",
            timer:3000
        }) .then( function () {
            window.location = "http://localhost/phoneBook";
        })
    </script>
<?php endif; ?>