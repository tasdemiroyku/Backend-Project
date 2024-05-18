<?php
    session_start();
    require "db.php";

    if(!isset($_SESSION["user"]) || $_SESSION["user"]["type"] != "C"){
        $_SESSION["titles"] = array();
        $_SESSION["user"] = NULL;
        $_SESSION["quantities"] = array();
        $_SESSION["total"] = NULL;
        header("Location: signIn.php");
        exit;
    }

    const PAGESIZE = 4;

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if( isset($_GET["title"])){
            $title = $_GET["title"];
            $flag = 0;
            foreach($_SESSION["titles"] as $i => $value){
                if($title == $value){
                    $error = "Already Added!!";
                    $flag = 1;
                }
            }
            if($flag == 0){
                array_push($_SESSION["titles"], $title);
                array_push($_SESSION["quantities"], 0);
            }
            
        }
    }
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
        if($edit == "e"){
            header("Location: editProfile.php");
            exit;
        } else if($logOut == "l"){
            $_SESSION["titles"] = array();
            $_SESSION["user"] = NULL;
            $_SESSION["quantities"] = array();
            $_SESSION["total"] = NULL;
            header("Location: signIn.php");
            exit;
        } else if($shoppingCart == "s"){
            header("Location: shoppingCart.php");
            exit;
        }
        $_SESSION["str"] = filter_var($str, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    $str = $_SESSION["str"];
    $user = $_SESSION["user"];

    $page = isset($_GET["page"]) ? $_GET["page"] : 1 ;
    $start =($page - 1) * PAGESIZE ;
    $stmt = $db -> prepare("select * from products where city = ? and district = ? and expiration_date > '2021-01-01' and title like ? UNION select * from products where city = ? and district != ? and title like ? and expiration_date > '2021-01-01' limit $start, 4");
    $stmt->execute([$user["city"], $user["district"], "%{$str}%",$user["city"], $user["district"], "%{$str}%"]);
    $products = $stmt->fetchAll();
    $size = count($products);
    $pageStmt = $db->prepare("select count(*) sz from products where city = ? and title like ? and expiration_date > '2021-01-01'");
    $pageStmt->execute([$user["city"], "%{$str}%"]);
    $pageSize = ceil($pageStmt->fetch()["sz"] / PAGESIZE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="consumer.css">
    <title>Consumer Profile Page</title>
    <style>
        .instruction{
            display: flex;
            justify-content: center;
            vertical-align: center;
            height: auto;
        }
    </style>
</head>
<body>
    <?php
        if($error != ""){
            echo $error;
        }   
    ?>
    <form action="" method="post">
        <button class="edit" name="edit" value="e">Edit Profile</button>
        <button class="edit" name="logOut" value="l">Log Out</button>
        <button class="edit" name="shoppingCart" value="s">Shopping Cart</button>
    </form>
    <div class="container"> 
        <div class="searchDiv">
            <form class="form" action="" method="post">
                <input type="text" name="str" id="str">
                <button>Search</button>
            </form>
        </div>
        <div class="instruction">
            <b>To See All Available Products, Please Search Empty String ("").</b>
        </div>
        <table>
            <tr>
                <td class="icons">
                    <?php
                        if ( $page > 1) {
                            echo "<a href='?page=" . ($page-1) . "'><img class='pr prevBlank' src='prev.png'></a>" ;
                        } else {
                            echo "<img src='prev.png' class='pr prevBlank'>" ;
                        }
                    ?>
                    </td>
                <?php
                
                for ($i=0 ; $i<$size ; $i++) {
                        echo "<td class='product'>" ;
                        echo "<img src='./images/{$products[$i]['image']}'>" ;
                        echo "<span class='title'><b>{$products[$i]['title']}</b></span><br>";
                        if($products[$i]["expiration_date"] <= "2021-07-01"){
                            echo "<div class='price'><b>{$products[$i]['discounted_price']} TL</b></div><br>" ;
                        } else {
                            echo "<div class='price'>{$products[$i]['normal_price']} TL</div><br>" ;
                        }
                        echo "<div><a class='btn' href='?title={$products[$i]['title']}&page=$page'>Add</a></div>" ;
                        echo "</td>" ;
                }
                for ( $i=0; $i< ( $size - PAGESIZE); $i++) {
                    echo "<td></td>" ;
                }
                ?>
                    <td class="icons">
                        <?php
                        if ( $page < $pageSize) {
                            echo "<a href='?page=" . ($page+1) . "'><img class='pr' src='next.png'></a>" ;
                        } else {
                            echo "<img src='next.png' class='pr'>" ;
                        }
                ?>
                    </td>
            </tr>
        </table>
        <div class='pages'>
            <?php
                for ( $i=1; $i <= $pageSize; $i++) {
                    echo "<a class='pageNo";
                    if ( $i == $page) {
                        echo ' active' ;
                    }
                    echo  "' href='?page=$i'>$i</a>" ;
                    
                }
            ?>
        </div> 
    </div> 
</body>
</html>