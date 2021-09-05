<?php
require_once "include/include.php";
$queryLogIn=null;
$query = null ;
$msgErr = false;
$msgSuccess = false;


$msgErr=null;
$msg=null;
if (isset($_GET["key"]) && !empty($_GET["key"]))
{
    if (isset($_POST["submit"])) {

            $msgErr=Users::changePass($_POST["pass"] , $_POST["rePass"], $_GET["key"]);


            if ($msgErr==0)
            {
                $msgErr=true;
                $msg="رمز خود را به درستی وارد کنید";
            }
            else
            {
                $msgSuccess=true;
                $msg="رمز شما تغییر کرد";
                Users::nullReset($_GET["key"]);
            }
        }
}
else
{
    $msg="مبدا درست نیست.";
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



                            <div class="boxBody col-12 pt-3">
                                <form id="loginUser" action="#" method="post" class="text-light mt-5">

                                    <fieldset>

                                        <div class="form-group text-right">
                                            <label for="" class="">رمز عبور</label>
                                            <input type="text" name="pass" class="form-control text-right" placeholder="رمز عبور">
                                            <small class="err"></small>
                                        </div>
                                        
                                        <div class="form-group text-right">
                                            <label for="" class=""> تکرار رمز عبور</label>
                                            <input type="text" name="rePass" class="form-control text-right" placeholder="تکرار رمز عبور">
                                            <small class="err"></small>
                                        </div>
                                        
                                       


                                        <div class="form-group text-center ">
                                          <button class="btn btn-cancel"><a href="http://localhost:8080/backend-web/phoneBook">انصراف </a></button>
                                          
                                        <input type="submit"   class="btn btn-submit  " name="submit" value="تغییر رمز" >   
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
        }).then( function (){
            window.location="http://localhost:8080/backend-web/phoneBook/login";
        })
    </script>
<?php endif; ?><?php
