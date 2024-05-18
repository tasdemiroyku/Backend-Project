<?php

    $dsn = "mysql:host=localhost;dbname=test;charset=utf8mb4";
    $user = "root";
    $password = "root";

    try{
        $db = new PDO($dsn, $user, $password);
    } catch(PDOException $e){
        header("Location: error.php");
        exit;
    }

    function checkUser($email, $pass, &$user){
        global $db;

        $stmt = $db -> prepare("select * from users where email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if($user){
            return password_verify($pass, $user["password"]);
        }
        return false;
    }

    function isAuthenticated() {
        return isset($_SESSION["user"]);
    }

    function getUser($email){
        global $db;
        $stmt = $db->prepare("select * from users where email = ?");
        $stmt->execute([$email]);
         return $stmt->fetch();
    }

    function getProduct($title){
        global $db;
        $stmt = $db->prepare("select * from products where title = ?");
        $stmt->execute([$title]);
        return $stmt->fetch();
    }