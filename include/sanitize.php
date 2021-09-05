<?php

function sanitize($value){
    $level1 = trim($value);
    $level2 = strip_tags($level1);
    return $level2;
}
