<?php //mysqli Classes for database handling
ob_start();
/* Copyright (C) 2000 Paulo Assis <paulo@coral.srv.br>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.  */

//include("siteconfig.inc.php");

class phpdbform_db {
    var $database;
    var $db_host;
    var $auth_name;
    var $auth_pass;
    var $conn;

    function phpdbform_db( $database_name, $database_host, $user_name, $user_passwd ) {
        $this->database = $database_name;
        $this->db_host =  $database_host;
        $this->auth_name = $user_name;
        $this->auth_pass = $user_passwd;
    }
    function show_error($msg)
    {   print $msg;
        //if($_SESSION['SuperUser'])
        print mysqli_error();
        //else print 'خطا در اطلاعات';
    }
    // Connect to database
    function connect()
    {
        global $error_msg;
        $this->conn = mysqli_connect( $this->db_host, $this->auth_name, $this->auth_pass, $this->database);
        $this->conn->set_charset("utf8");
        if( !$this->conn ) {
            $this->show_error(mysqli_connect_error());
            //$this->show_error('Error in Connect to Database');
            return false;
        }
        if( !mysqli_select_db( $this->conn , $this->database) ) {
            $this->$error_msg[1];
            return false;
        }
        return true;
    }
    // Close the connection
    function close()
    {
        mysqli_close($this->conn);
    }
    // Do a query
    function query($stmt,$msg='')
    {
        global $error_msg;
        //echo "<br>".$stmt."<br>";
        $ret = mysqli_query( $this->conn , $stmt);
        if(!$ret) $this->show_error($error_msg[$msg]);
        return $ret;
    }
    function unbuffered_query($stmt,$msg='')
    {
        global $error_msg;
        //echo "<br>".$stmt."<br>";
        $ret = mysqli_unbuffered_query( $this->conn , $stmt);
        if(!$ret) $this->show_error($error_msg[$msg]);
        return $ret;
    }
    function real_escape_string($stmt)
    {   $ret = mysqli_real_escape_string( $this->conn ,$stmt);
        return $ret;
    }
    function fetch_array( $ret ,$my=MYSQLI_BOTH)
    {
        return mysqli_fetch_array($ret,$my);
    }
    function fetch_row( $ret )
    {
        return mysqli_fetch_row($ret);
    }

    function results_to_array($query)
    {
        $result=mysqli_query($query);
        $res_array = array();
        for ($count=0; $row = @mysqli_fetch_array($result); $count++)
            $res_array[$count] = $row;
        return $res_array;
    }

    function fetch_hsarray( $ret )
    {
        $results1 = array();
        $results2 = array();
        mysqli_fetch_array($ret,$results1);
        for ($i=0;$i<count($results1);$i++)
            $results2[mysqli_fetch_seek($ret, $i)]=$results1[$i];
        return $results2;
    }

    function list_tables( $ret )
    {
        mysqli_list_tables($ret);
    }
    function free_result( $ret )
    {
        mysqli_free_result($ret);
    }
    function num_fields( $ret )
    {
        return mysqli_num_fields($ret);
    }
    function field_len( $ret, $num )
    {
        return mysqli_field_len($ret, $num);
    }
    function field_name( $ret, $num )
    {
        return mysqli_field_name( $ret, $num );
    }
    function num_rows( $ret )
    {
        return mysqli_num_rows($ret);
    }
    function fetch_assoc($res)
    { return mysqli_fetch_assoc($res);
    }
    function fetch_field($res)
    { return mysqli_fetch_field($res);
    }
    function field_allow_null( $ret, $num )
    {
        //$ret = mysqli result set handle
        //$num = record number
        $meta = mysqli_fetch_field ($ret, $num);
        if (!$meta) {
            //Information about field not available.
            return -1;
        }
        if ($meta->not_null == 1) return false;
        else return true;
    }
    // Christophe Conduché
    function insert_id()
    {
        return mysqli_insert_id( $this->conn );
    }
    function field_type( $ret, $num )
    {
        return mysqli_field_type( $ret, $num );
    }

    function get_fields( $table )
    {
        // returns an array with filed properties
        $ret = array();
        $lfields = mysqli_query("SHOW FIELDS FROM $table",$this->conn);
        while($row=mysqli_fetch_array($lfields))
        {
            $field = $row["Field"];
            $type = strtolower($row["Type"]);
            $type = stripslashes($type);
            $type = str_replace("binary","",$type);
            $type = str_replace("zerofill","",$type);
            $type = str_replace("unsigned","",$type);
            $length = $type;
            $length = strstr($length,"(");
            $length = str_replace("(","",$length);
            $length = str_replace(")","",$length);
            $length = (int)chop($length);
            $type = chop(eregi_replace("\\(.*\\)", "", $type));
            //print "Field: $field - mysqli: ${row["Type"]} - Type: $type - Length: $length<br>";
            $ret[$field]["type"]=$type;
            $ret[$field]["maxlength"]=$length;
        }
        return $ret;
    }
    function get_new_id($Table,$field)
    {
        $r=$this -> query("select max($field) from $Table",'');
        $row=$this -> fetch_row($r);
        if(!isset($row[0]))
            $return=1;
        else $return=$row[0]+1;
        return $return;
    }

    function get_from_where($Fields,$TableName,$Where='',$RowCount=0,$Showq=false)
    {$q = "select $Fields from $TableName ";
        if (trim($Where)!='')
            $q .= ' where '.$Where;
        $ret = $this->query($q,'');
        if($Showq and SuperUser())
            echo "<div style='color:blue' dir=ltr>$q</div>";
        if($RowCount > 0)
        {
            while($row = $this->fetch_array($ret))
            {
                if(stristr($Fields,',')==False)
                { $ResArr[] = $row[0];
                    if($Showq)echo "Yes STRiSTR";}
                else
                {$ResArr[] = $row;
                    if($Showq)echo "No STRiSTR";}
            }
            $this -> free_result($ret);
            return $ResArr;
        }
        else
        {$row = $this->fetch_array($ret);
            $this -> free_result($ret);
            if(stristr($Fields,',')==False)
                return $row[0];
            else
                return $row;
        }
    }

    function select_to_array($q, $start, $end)
    {
        $ret = $this -> query( $q, "filling contact listing" );
        $i=0;
        while($row = $this ->fetch_row( $ret ))
        {
            if($i >= $start and $i <= $end)  //  if start and end is set
            {
                $arr[$i]=$row;
            }

            $i++;
        }

        $ret -> free();
        //print_r($arr);
        return $arr;
    }

}
ob_end_flush();
?>
