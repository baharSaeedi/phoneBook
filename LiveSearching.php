<?php
require_once "include/include.php";

    $liveValue = mb_strtolower($_POST["search"]);
    $userId= $_POST["userId"];
    $contactValue= Contacts::getAllContacts($userId);

    $str="";
    foreach ($contactValue as $contact)
    {
        if (!is_numeric($liveValue))
        {
            if (mb_strpos($contact->name, $liveValue) !== false)
            {
                $gName=Contacts::getContactGroup($contact->gid);
                $bold="<strong class='icons'>$liveValue</strong>";
                $keyBold = str_replace($liveValue,$bold,$contact->name);
                $str .="<div class='searchBox mt-3 text-white d-flex flex-row-reverse justify-content-between'> 
                                                            <input type='hidden' name='token1' value='$contact->id'>
                                                            <p class='mr-3'>$keyBold</p>
                                                            <p class='ml-2'>
                                                             <a class='delCon icons mr-3'  data-direct='?Mode=delete&cid=" . $contact->id. "'><i class='fa fa-trash'></i></a>
                                                            <a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                            <a class='showCon icons' data-toggle='modal' data-target='#MyModal'  href='#'><i class='fa fa-eye'></i></a></p>
                                                       </div>";
            }
        }
        else
        {

            $infos=$contact->info;
            if (is_array($infos))
            {
                foreach ($infos as $info)
                {

                    if (mb_strpos($info["info"], $liveValue) !== false) {
                        $gName = Contacts::getContactGroup($contact->gid);
                        $bold = "<strong class='icons'>$liveValue</strong>";
                        $keyBold = str_replace($liveValue, $bold, $info["info"]);
                        $str .= "<div class='searchBox mt-3 text-white d-flex flex-row-reverse justify-content-between'>
                                                        <input type='hidden' name='token1' value='$contact->id'>
                                                        <p class='mr-3'>$contact->name</p>
                                                        <p>$keyBold</p>
                                                        <p>
                                                        <a class='delCon icons mr-3'  data-direct='?Mode=delete&cid=" . $contact->id . "'><i class='fa fa-trash'></i></a>
                                                        <a class='editCon icons mr-3' href='#'><i class='fa fa-edit'></i></a>
                                                        <a class='showCon icons' data-toggle='modal' data-target='#MyModal'  href='#'><i class='fa fa-eye'></i></a></p>
                                                   </div>";
                    }
                }
            }
        }
    }
    if ($str=="")
    {
        $str .= "<h4 class='mx-auto text-white mt-5 text-center'>.مخاطبی با این اطلاعات یافت نشد</h4>";
    }
    echo $str;


?>