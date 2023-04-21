<?php

function createConnection(){
    $con = new mysqli('localhost', 'root', '', 'peluqueria');
    $con->setCharset('utf8');
    return $con;
}