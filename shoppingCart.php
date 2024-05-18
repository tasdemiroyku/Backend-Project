<?php
    session_start();
    require_once "db.php";

    if(!isset($_SESSION["user"]) || $_SESSION["user"]["type"] != "C"){
        $_SESSION["user"] = NULL;
        $_SESSION["product"] = NULL;
        header("Location: signIn.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["title"])){
        $title = $_GET["title"];
        foreach($_SESSION["titles"] as $i => $value){
            if($title == $value){
                $pr = getProduct($title);
                if($pr["expiration_date"] <= "2021-07-01"){
                    $_SESSION["total"] -= floatval($pr["discounted_price"]) * $_SESSION["quantities"][$i];
                } else {
                    $_SESSION["total"] -= floatval($pr["normal_price"]) * $_SESSION["quantities"][$i];
                }

                unset($_SESSION["quantities"][$i]);
                unset($_SESSION["titles"][$i]);
            }
        }
        //$_SESSION["totalCheck"] = NULL;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
        if($goback == "g"){
            $_SESSION["totalCheck"] = NULL;
            header("Location: consumer.php");
            exit;
        } else if($calculate == "c"){
            $k=0;
            foreach($_SESSION["quantities"] as $i => $value){
                $_SESSION["quantities"][$i] = $quantityBox[$k];
                $k++;
            }
            $total = 0;
            $k = 0;
            foreach($_SESSION["titles"] as $i => $value){
                $pr = getProduct($value);
                if($pr["expiration_date"] <= "2021-07-01"){
                    $total += floatval($pr["discounted_price"]) * $quantityBox[$k];
                } else {
                    $total += floatval($pr["normal_price"]) * $quantityBox[$k];
                }
                $k++;
            }
            $_SESSION["totalCheck"] = 1;
            $_SESSION["total"] = $total;
        } else if($buy = "b" && isset($_SESSION["totalCheck"])){
            $k = 0;
            foreach($_SESSION["titles"] as $i => $value){
                $pr = getProduct($value);
                $stmt = $db->prepare("update products set stock = ? where title = ?");
                $stmt->execute([intval(($pr["stock"]) - $quantityBox[$k]), $pr["title"]]);
                $pr = getProduct($value);
                if($pr["stock"] == 0){
                    $stmt = $db->prepare("delete from products where title = ?");
                    $stmt->execute([$pr["title"]]);
                }
                $k++;
            }
            $_SESSION["titles"] = array();
            $_SESSION["quantities"] = array();
            if($_SESSION["total"] == 0){
                $_SESSION["totalCheck"] = NULL;
                $error = "Cart is Empty!!";
            }
            $total = 0;
            $_SESSION["total"] = $total;
            $_SESSION["totalCheck"] = NULL;
        } else {
            $error = "Please Calculate First!!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="shoppingCart.css">
    <title>Shopping Cart</title>
    <style>
        #quantityBox{
            margin-top: 40px;
            font-size: 20px;
            list-style-type: none;
        }
        #delBtn{
            margin-top: 40px;
        }
        #total{
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <button class="btn" name="goback" value="g">Go Back</button>
    </form>
    <h2>Shopping Cart</h2>
    <div class="container">
        <form action="?" method="post" class="form">
            <div class="infoBox">
                <?php
                    foreach($_SESSION["titles"] as $k => $title){
                        $product = getProduct($title);
                        if($product){
                            echo "<div class='info'>";
                            echo "<img src='./images/{$product['image']}'>" ;
                            echo "<span class='title'><b>{$product['title']}</b></span><br>";
                            if($product["expiration_date"] <= "2021-07-01"){
                                echo "<div class='price'><b>{$product['discounted_price']} TL</b></div><br>" ;
                            } else {
                                echo "<div class='price'>{$product['normal_price']} TL</div><br>" ;
                            }
                            echo "<select name='quantityBox[]' id='quantityBox'>";
                                for($i=0;$i<=$product["stock"];$i++){
                                    if(intval($_SESSION["quantities"][$k]) == $i){
                                        echo "<option value='$i' selected>$i</option>";
                                    } else {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    
                                }
                            echo "</select>";
                            echo "<div id='delBtn'><a class='btn' href='?title={$product['title']}'>Del</a></div>" ;
                        echo "</div>";
                        }
                    }
                ?> 
            </div>
            <div class="cart">
                <button class="cartBtn" id="calcBtn" name="calculate" value="c">Calculate Total</button>
                <p id = "total">Total: <?= $_SESSION["total"]?> TL</p>
                <button class="cartBtn" id="buyBtn" name="buy" value="b">Buy</button>
                <?php
                    if($error != ""){
                        echo "<span class='error_msg' style='color: black; font-weight: bold; padding-top: 10px;'>$error</span>";
                    }
			    ?>
            </div>
        </form>
    </div>
</body>
</html>