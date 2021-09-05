<?php
require_once  "include/include.php";




if (isset($_POST["moreCon"]))
{
    $contacts=Contacts::getCountContacts($_SESSION["userInfo"]["id"],5,$_POST["moreCon"]);

    $str="";
    if ($contacts!=0)
    {
        foreach ($contacts as $contact)
        {
            $str .="<div class='contactBox mt-3 text-white d-flex flex-row-reverse justify-content-between'> 
                                                            <input type='hidden' name='token1' value='$contact->id'>
                                                            <p class='mr-3'>$contact->name</p>
                                                            <p class='ml-2'>
                                                            <a class='delCon icons mr-3' href='#' ><i class='fa fa-trash'></i></a>
                                                            <a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                            <a class='showCon icons' data-toggle='modal' data-target='#MyModal' href='#'><i class='fa fa-eye'></i></a></p>
                                                       </div>";

        }
    }

    if ($str=="")
    {
        $str .= "<h4 class='mx-auto text-white mt-5 text-center'>.مخاطب دیگری وجود ندارد</h4>";
    }



    echo $str;
}

$infoStr=array(
    "1" => "",
    "2" => "",
    "3" => "",
);
$str="";
if (isset($_POST["show"]))
{
    $myContact=Contacts::getContactById($_SESSION["userInfo"]["id"],$_POST["show"]);


    if (isset($_SESSION["userInfo"]))
    {

        if (!empty($myContact))
        {
            if (!is_null($myContact->imageAdd) )
            {
                $str .= "
                   
                          <div class='d-flex text-center justify-content-center'>
                           <div class='contactPic  text-center ml-3 mt-3 '>
                                <img src='$myContact->imageAdd'   class='picture mt-2'/>
                           </div>
</div>
                            <div>
                ";
            }
            else
            {
                $str .= "<div>
                                                            <p><i class='fa fa-user-circle text-center mx-auto mt-5 icons' style='font-size: 100px'></i></p>";
            }

            $str .= "<input type='hidden' name='token1' value='$myContact->id'>
                                                            <p class='mr-2  h1 mx-auto text-white '>$myContact->name</p>
                                                            <p class='ml-2'>
                                                            <a class='delCon icons mr-3' href='#'><i class='fa fa-trash'></i></a>
<a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                            <a class='showCon icons' data-toggle='modal' data-target='#MyModal' href='#'><i class='fa fa-eye-slash'></i></a></p>

</div>";
            $infos=$myContact->info;
            if (is_array($infos))
            {
                foreach ($infos as $info)
                {
                    if ($info["type"]==1)
                    {

                        $pType=Contacts::getPhoneType($info["phoneTypeId"]);
                        $infoStr[$info["type"]] .= "
<div class='text-right  d-flex  justify-content-around align-items-center mb-1 infos'> 
<p class='text-white'> ".$info["info"]."</p> 
<p class='text-muted'>$pType</p>
</div>
";

                    }
                    else
                    {
                        $Type=Contacts::GetType($info["type"]);
                        $infoStr[$info["type"]] .= "
<div class='text-right  d-flex justify-content-around mb-1 infos'> 
<p class='text-white'> ".$info["info"]."</p> 
<p class='text-muted pr-3'>$Type</p>
 </div>
";
                    }
                }
            }

            foreach ($infoStr as $string)
            {
                if (!empty($string))
                {
                    $str .= "<div class='text-right infoBox'> " . $string ."</div>";
                }
            }

            $group=Contacts::getContactGroup($myContact->gid);
           if ($group==!false)
           {
               $str .= "
                   <div class='text-right  infoBox d-flex justify-content-around'> 
<p class='text-white'> $group</p> 
<p class='text-muted pr-3'>گروه</p>
 </div>
";
           }

        }
        else
        {
            $str .= "<h4 class='mx-auto text-white mt-5'>.شما مخاطبی اضافه نکرده اید</h4>";
        }
    }
    else
    {
        $str .="<a href='login.php' class='mx-auto text-white  h5 mt-5 text-center'>.لطفا به حساب کاربری خود وارد شوید</a>";
    }


    echo $str;
}
