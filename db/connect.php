<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "quiz_app_db";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        

        echo "connected to server";
    } catch (PDOException $e) {
        echo "connected failed: " . $e->getMessage();
    }
?>