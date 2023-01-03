<?php

try{
    $pdo = new PDO('mysql://hostname=localhost;dbname=php_pdo','root','',
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', // intial command that will be executed when connecting to mysql
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
}catch(PDOException $e){
    echo $e->getMessage();
}
