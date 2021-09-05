<?php

include_once "MainClass.class.php";
class Contacts extends MainClass
{
    protected $data = array(
        "id" => 0 ,
        "name" => "" ,
        "uid" => "" ,
        "gid" => "" ,
        "imageAdd" => "",
        "info" => []
    );

    public static function getInfo($cid){
        $connect = self::connect();
        $sql = ("select *  from " . DB_INFO  ." WHERE  `cid` = ?  ORDER BY `type`" );
        $result = $connect->prepare($sql);
        $result->bindValue(1,$cid);
        $result->execute();
        if($result->rowCount()){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $info[] = $row;
            }
            $ret = $info ;
        }
        else
            $ret = 0 ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getAllContacts($id){
        $connect = self::connect();
        $sql = ("select *  from " . DB_CONTACTS  ." WHERE  `uid` = ? " );
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if($result->rowCount()){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $infos=self::getInfo($row["id"]);
                $row["info"]=$infos;
                $contacts[] = new Contacts($row);
            }

            $ret = $contacts ;
        }
        else
            $ret = 0 ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getCountContacts($id, $limit=0 , $start=0){
        $connect = self::connect();
        $limiter = $limit > 0 ? "LIMIT $start , $limit " : "";
        $sql = ("select *  from " . DB_CONTACTS ." WHERE  `uid` = ?  $limiter " );
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if($result->rowCount()){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $infos=self::getInfo($row["id"]);
                $row["info"]=$infos;
                $contacts[] = new Contacts($row);
            }
            $ret = $contacts ;
        }
        else
            $ret = 0 ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getContactById($uid , $id){
        $connect = self::connect();
        $sql = ("select *  from " . DB_CONTACTS ." WHERE  `id` = ? AND `uid` = ?   " );
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->bindValue(2,$uid);
        $result->execute();
        if($result->rowCount()){
           $row = $result->fetch(PDO::FETCH_ASSOC) ;
           $infos=self::getInfo($row["id"]);
           $row["info"]=$infos;
           $contact=new Contacts($row);
           $ret = $contact ;
        }
        else
            $ret = " " ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getContactGroup($gid){
        $connect = self::connect();
        $sql = ("select gName from ". DB_GROUP . " WHERE `id` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$gid);
        $result->execute();


        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["gName"];
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function GetType($tid)
    {
        $connect = self::connect();
        $sql = ("select infoName from ". DB_TYPE . " WHERE `id` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$tid);
        $result->execute();


        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["infoName"];
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function infoTypes()
    {
        $connect = self::connect();
        $sql = ("select *  from " . DB_TYPE  );
        $result = $connect->prepare($sql);
        $result->execute();
        if($result->rowCount()){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $infoType[] = $row;
            }
            $ret = $infoType ;
        }
        else
            $ret = 0 ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getGroups()
    {
        $connect = self::connect();
        $sql = ("select *  from " . DB_GROUP  );
        $result = $connect->prepare($sql);
        $result->execute();
        if($result->rowCount()){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC) ;
            foreach ($rows as $row){
                $group[] = $row;
            }
            $ret = $group ;
        }
        else
            $ret = 0 ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getPhoneType($tid){
        $connect = self::connect();
        $sql = ("select typeName from ". DB_PHONETYPE . " WHERE `id` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$tid);
        $result->execute();


        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["typeName"];
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }

    public static function getImageAdd($id){
        $connect = self::connect();
        $sql = ("select imageAdd from ". DB_CONTACTS . " WHERE `id` = ?");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();


        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["imageAdd"];
        }
        else
            $ret = false ;
        self::disconnect($connect);
        return $ret ;
    }

    public static  function InsertContact(  $uid , $name   , $gid=1 , $imageAdd=null){
        $connect = self::connect();
        $name=sanitize($name);

        $sql=("INSERT `".DB_CONTACTS."` SET `name` = ? , `gid` = ? , `imageAdd` = ? ,  `uid` = ? ");


        $result = $connect->prepare($sql);
        $result->bindValue(1,$name);
        $result->bindValue(2,$gid);
        $result->bindValue(3,$imageAdd);
        $result->bindValue(4,$uid);

        $result->execute();
        if($result->rowCount()){
            $lastID=$connect->lastInsertId();
            return $lastID;
        }
        else
           echo error_reporting(E_ALL);
        self::disconnect($connect);
    }

    public static  function InsertInfo($cid,$typeId,$info , $phoneType=0){
        $connect = self::connect();
        $info=sanitize($info);

        $sql=("INSERT `".DB_INFO."` SET `cid` = ? , `type` = ? , `info` = ? ,  `phoneTypeId` = ? ");


        $result = $connect->prepare($sql);
        $result->bindValue(1,$cid);
        $result->bindValue(2,$typeId);
        $result->bindValue(3,$info);
        $result->bindValue(4,$phoneType);

        $result->execute();
        if($result->rowCount()){

        }
        else
            echo false;
        self::disconnect($connect);
    }

    public static function deleteContact($cid)
    {
        self::deleteInfoByCid($cid);
        $connect = self::connect();
        $sql = ("DELETE  from ". DB_CONTACTS ." WHERE `id` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$cid);
        $result->execute();
        if($result->rowCount()){
            return true;
        }
        else
            return false ;
        self::disconnect($connect);

    }

    public static function deleteInfoByCid($cid)
    {
        $connect = self::connect();
        $sql = ("DELETE  from ". DB_INFO ." WHERE `cid` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$cid);
        $result->execute();
        if($result->rowCount()){
            return true;
        }
        else
            return false ;
        self::disconnect($connect);

    }

    public static function deleteInfo($infoId)
    {
        $connect = self::connect();
        $sql = ("DELETE  from ". DB_INFO ." WHERE `infoId` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$infoId);
        $result->execute();
        if($result->rowCount()){
            return true;
        }
        else
            return false ;
        self::disconnect($connect);

    }

    public static function GetGid($gName)
    {
        $connect = self::connect();
        $sql = ("select `id` from ". DB_GROUP ." WHERE `gName` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$gName);
        $result->execute();
        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["id"];
        }
        else
            return false;
        self::disconnect($connect);
    }

    public static function handleGroup($gName)
    {
        $exist=self::GetGid($gName);
        if ($exist!==false)
        {
            return $exist;
        }
        else
        {
            $connect = self::connect();
            $sql = ("INSERT ".DB_GROUP." SET `gName` = ? ");
            $result = $connect->prepare($sql);
            $result->bindValue(1,$gName);
            $result->execute();
            $gid=self::GetGid($gName);
            return $gid;
        }

        self::disconnect($connect);
    }

    public static function GetPid($tName)
    {
        $connect = self::connect();
        $sql = ("select `id` from ". DB_PHONETYPE ." WHERE `typeName` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$tName);
        $result->execute();
        if($result->rowCount()){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["id"];
        }
        else
            return false;
        self::disconnect($connect);
    }

    public static function infoExist($id , $cid)
    {
        $connect = self::connect();
        $sql = ("select `*` from ". DB_INFO ." WHERE `infoId` = ? AND cid = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->bindValue(2,$cid);
        $result->execute();
        if($result->rowCount()){
          return true;
        }
        else
            return false;
        self::disconnect($connect);
    }

    public  static function editContact( $cid, $name , $gid=1 , $imageAdd=null )
    {
        $connect = self::connect();
        $name=sanitize($name);
        $sql = ("UPDATE `".DB_CONTACTS."` SET `name` = ? , `gid` = ? ,  `imageAdd`  = ? WHERE `id` = ? ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$name);
        $result->bindValue(2,$gid);
        $result->bindValue(3,$imageAdd);
        $result->bindValue(4,$cid);
        $result->execute();
        if($result->rowCount()){
            return 1;
        }
        else
            return 0 ;
        self::disconnect($connect);
    }

    public  static function editInfo( $infoId , $info  , $pid=0)
    {
        $connect = self::connect();
        $sql = ("UPDATE `".DB_INFO."` SET `info` = ? , `phoneTypeId` = ?   WHERE `infoId` = ?   ");
        $result = $connect->prepare($sql);
        $result->bindValue(1,$info);
        $result->bindValue(2,$pid);
        $result->bindValue(3,$infoId);
        $result->execute();
        if($result->rowCount()){
            return 1;
        }
        else
            return 0 ;
        self::disconnect($connect);
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