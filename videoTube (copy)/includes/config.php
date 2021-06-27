<?php

ob_start();
session_start();
date_default_timezone_set("Europe/Belgrade");

error_reporting(E_ALL);
ini_set("display_errors",1);

    try {

        $con = new PDO("mysql:dbname=VideoTube;host=localhost", "srki", "111");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
     }
    catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }


