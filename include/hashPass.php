<?php
function hashpass($password,$type="sha1"){

    switch ($type){
        case "crc32":
            return crc32($password);
        case "md5":
            return md5($password);
        case "sha1":
            return sha1($password);

    }

}
?>
