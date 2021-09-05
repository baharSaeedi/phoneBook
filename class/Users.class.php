<?php

include_once "MainClass.class.php";
class Users extends MainClass
{
    protected $data = array(
        "id" => 0 ,
        "firstName" => "" ,
        "lastName" => "" ,
        "userName" => "" ,
        "email" => "" ,
        "pass" => "" ,
        "activationKey" => "" ,
        "reset_pass_key" => "" ,
        "status" => "",
        "imageAdd" => ""
    );


    public static function getAllUsers(){
        $connect = self::connect();
        $sql = ("select * from " . DB_USERS );
        $result = $connect->prepare($sql);
        $result->execute();
        if($result->rowCount()){
            $users = array();
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $users[] = new Users($row);
            }
            $ret = $users ;
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }



    public static  function InsertUser($firstName,$lastName,$userName,$email,$password,$activationKey , $imageAdd=null){
        $connect = self::connect();
        $firstName = sanitize($firstName);
        $lastName = sanitize($lastName);
        $userName = sanitize($userName);
        $email = sanitize($email);
        $password = sanitize($password);
        $password = hashpass($password);
        $activationKey = sanitize($activationKey);

        $sql = ("INSERT ".DB_USERS." SET `firstName` = ? , `lastName` = ? , `userName` = ? , `email` = ? , `pass` = ?   ,`activationKey`=?  , `imageAdd` = ?");


        $result = $connect->prepare($sql);
        $result->bindValue(1,$firstName);
        $result->bindValue(2,$lastName);
        $result->bindValue(3,$userName);
        $result->bindValue(4,$email);
        $result->bindValue(5,$password);
        $result->bindValue(6,$activationKey);
        $result->bindValue(7,$imageAdd);

        $result->execute();

        return $result ;
    }


    public static function isUserExist($email)
    {
        $connect = self::connect();
        $email = sanitize($email);
        $sql = ("SELECT `id` FROM ".DB_USERS." WHERE `email` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$email);
        $result->execute();
        if($result->rowCount() > 0){
            return true;
        }
        return false ;
    }

    public static function isUserAvailable($email)
    {
        $connect = self::connect();
        $sql = ("SELECT `status` FROM ".DB_USERS." WHERE `email` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$email);
        $result->execute();
        if($result->rowCount() > 0){
            $row = $result->fetch(PDO::FETCH_ASSOC) ;
            return $row["status"];
        }
        return false ;
    }



    public  static  function sendMail($current_user_email,$mail_subject,$mail_body){
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer(true);

        try{
            $mail->SMTPDebug = 2 ;
            $mail->IsSMTP() ;
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true ;
            $mail->Username = "saeeeedi99.example@gmail.com";
            $mail->Password = "@123456rb";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->IsHTML(true);
            $mail->CharSet = "utf-8";
            $mail->FromName = "از طرف phoneBook";
            $mail->ContentType = "text/html;charset='utf-8'";

            $mail->Subject = $mail_subject;
            $mail->Body =$mail_body;

            $mail->AddAddress($current_user_email,"cue");
            $mail->Send();
        }
        catch (Exception $err){
            echo "<div class='alert alert-warning col-6 mx-auto text-center'>ایمیل ارسال نشد </div>";
        }
        $mail->SmtpClose();
        return $mail;
    }


    public  static  function isSameActivationKey($activationKey){
        $connect = self::connect();
        $activationKey = sanitize($activationKey);
        $sql = ("SELECT `activationKey` FROM `".DB_USERS."` WHERE `activationKey` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$activationKey);
        $result->execute();
        if($result->rowCount()){
            return $result;
        }
        return false ;

    }



    public  static  function activateUser($activationKey=null , $email=null){
        $connect = self::connect();
        $sql = ("UPDATE `".DB_USERS."` SET `status` = ?   WHERE `activationKey` = ? and `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,1);
        $result->bindValue(2,$activationKey);
        $result->bindValue(3,$email);

        $result->execute();

        return $result;
    }



    public  static  function checkPassword($email = null , $password=null){
        $connect = self::connect();
        $email = sanitize($email);
        $password = sanitize($password);
        $password = hashpass($password);
        $sql = ("SELECT `email`  FROM `".DB_USERS."` WHERE `password` = ? and `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$password);
        $result->bindValue(2,$email);

        $result->execute();

        if($result->rowCount()){
            return 1;
        }
        return 0;
    }

    public static function CountUser()
    {
        $connect = self::connect();
        $sql = ("select count(id) from ". DB_USERS);
        $result = $connect->prepare($sql);
        $result->execute();
        if($result->rowCount()){
            $count = $result->fetch(PDO::FETCH_ASSOC) ;
            $count=$count["count(id)"];
        }
        else
            $count = false ;
        self::disconnect($connect);
        return $count ;
    }



    public static function deleteUser($id)
    {
        $connect = self::connect();
        $sql = ("DELETE  from ". DB_USERS ." WHERE `id` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if($result->rowCount()){
            return true;
        }
        else
            return false ;
        self::disconnect($connect);

    }
    public static function getUserById($id){
        $connect = self::connect();
        $sql = ("select * from ". DB_USERS . " WHERE `id` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC) ;
            $user = new Users($row);
            $ret = $user ;
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }

    public static  function LoginUser($email = null , $password = null ,$remember="off"){
        $connect = self::connect();
        $email = sanitize($email);
        $pass=$password;
        $password = sanitize($password);
        $password=hashpass($password);

        $sql = ("SELECT `id`  FROM `".DB_USERS."` WHERE `email` = ? AND `pass` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$email);
        $result->bindValue(2,$password);

        $result->execute();

        if($result->rowCount()){

            $row = $result->fetch(PDO::FETCH_ASSOC);
            $userSession = array(
                'signInKey' => true,
                'id' => $row["id"],
                'expireTime' => time() + 1200
            );
            $_SESSION["userInfo"] = $userSession ;


            if ($remember=="on")
            {
                setcookie('userEmail',$email, time() + 604800);
                setcookie('userPass',$pass , time() + 604800);
                setcookie('userRemember',$remember , time() + 604800);
            }
            else
            {
                setcookie('userPass',"", time() -3600);
                unset($_COOKIE["userPass"]);
                setcookie('userEmail',"", time() -3600);
                unset($_COOKIE["userEmail"]);
                setcookie('userRemember',"", time() -3600);
                unset($_COOKIE["userRemember"]);
            }


            return true;
        }
        return false ;
    }


    public  static function nullActivation($email)
    {
        $connect = self::connect();
        $sql = ("UPDATE `".DB_USERS."` SET `activationKey` = ?  WHERE `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,null);
        $result->bindValue(2,$email);
        $result->execute();

        return $result;
    }

    public  static function nullReset($key)
    {
        $connect = self::connect();
        $sql = ("UPDATE `".DB_USERS."` SET `reset-pass-key` = ?  WHERE `reset-pass-key` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,null);
        $result->bindValue(2,$key);
        $result->execute();

        return $result;
    }

    public  static function editUser(  $fName ,$lName , $uName ,$mobile , $status , $id )
    {
        $connect = self::connect();
        $sql = ("UPDATE `".DB_USERS."` SET `firstName` = ? , `lastName` = ? , `userName`  = ? , `mobile` = ? , `status` = ? WHERE `id` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$fName);
        $result->bindValue(2,$lName);
        $result->bindValue(3,$uName);
        $result->bindValue(4,$mobile);
        $result->bindValue(5,$status);
        $result->bindValue(6,$id);
        $result->execute();
        if($result->rowCount()){
            return 1;
        }
        else
            return 0 ;
        self::disconnect($connect);
    }


    public  static function changePass($pass1,$pass2,$resetKey ){
        $connect = self::connect();
        $pass1=trim($pass1);
        $pass1=sanitize($pass1);
        $pass2=trim($pass2);
        $pass2=sanitize($pass2);
        $pass=hashpass($pass1);
        if ($pass1==$pass2)
        {
            $sql = ("UPDATE `".DB_USERS."` SET `pass` = ?  WHERE `reset-pass-key` = ? ");
            $result = $connect->prepare($sql);
            $result->bindValue(1,$pass);
            $result->bindValue(2,$resetKey);
            $result->execute();

            return 1;
        }
        else
        {
            return 0;
        }
    }

    public  static  function setKeyChangePass($email,$resetKey){
        $connect = self::connect();
        $email = sanitize($email);
        $sql = ("UPDATE `".DB_USERS."` SET `reset-pass-key` = ?  WHERE `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$resetKey);
        $result->bindValue(2,$email);

        $result->execute();

    }


    public  static  function checkEmail($email = null ){
        $connect = self::connect();
        $email = sanitize($email);
        $sql = ("SELECT `email`  FROM `".DB_USERS."` WHERE `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$email);

        $result->execute();

        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row["email"]==$email)
            {
                return 1;
            }
        }
        return 0;
    }

    public static function  getUserId($email)
    {
        $connect = self::connect();
        $email = sanitize($email);
        $sql = ("SELECT `id`  FROM `".DB_USERS."` WHERE `email` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$email);

        $result->execute();

        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["id"];
        }
    }

    public static function saveReqChangePass($email,$id,$pass)
    {
        $connect = self::connect();
        $email = trim($email);
        $email = sanitize($email);
        $id = trim($id);
        $id = sanitize($id);
        $pass=trim($pass);
        $pass=sanitize($pass);
        $pass=hashpass($pass);

        $sql = ("INSERT ".DB_PASS_RESET." SET `uid` = ? , `email` = ? , `hash` = ?");


        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->bindValue(2,$email);
        $result->bindValue(3,$pass);

        $result->execute();

        return $result ;
    }


    public static function updateCookie($email,$pass)
    {
        $email = trim($email);
        $email = sanitize($email);

        $pass=trim($pass);
        $pass=sanitize($pass);

        setcookie('userEmail',"", time() -3600);
        setcookie('userEmail',$email, time() + 604800);
        setcookie('userPass',"", time() -3600);
        setcookie('userPass',$pass , time() + 604800);
        setcookie('userRemember',"on" , time() + 604800);
    }


}