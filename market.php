<?php
    session_start();
    require "db.php";

    if(!isset($_SESSION["user"]) || $_SESSION["user"]["type"] != "M"){
        $_SESSION["titles"] = array();
        $_SESSION["user"] = NULL;
        $_SESSION["quantities"] = array();
        $_SESSION["total"] = NULL;
        header("Location: signIn.php");
        exit;
    }

    const PAGESIZE = 4;

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["delete"]))
        {
            $title = $_GET["delete"];

            $stmt = $db->prepare("delete from products where title = ?");
            $stmt->execute([$title]);
        } else if(isset($_GET["update"])){
            $title = $_GET["update"];

            $product = getProduct($title);
            $_SESSION["product"] = $product;

            header("Location: updateProduct.php");
        }
        
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
        if($edit == "e"){
            header("Location: editProfile.php");
            exit;
        } else if($addProduct == "a"){
            header("Location: addProduct.php");
            exit;
        } else if($logOut == "l"){
            $_SESSION["user"] = NULL;
            $_SESSION["product"] = NULL;
            header("Location: signIn.php");
            exit;
        }
        
        $user = $_SESSION["user"];
    }
    
    $user = $_SESSION["user"];
    $page = isset($_GET["page"]) ? $_GET["page"] : 1 ;
    $start =($page - 1) * PAGESIZE ;
    $products = $db -> query("select * from products limit $start, 4")->fetchAll();
    $size = count($products);
    $pageSize = ceil($db->query("select count(*) sz from products")->fetch()["sz"] / PAGESIZE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="consumer.css">
    <title>Market Profile Page</title>
</head>
<body>
    <form action="" method="post">
        <button class="edit" name="edit" value="e">Edit Profile</button>
        <button class="edit" name="addProduct" value="a">Add Product</button>
        <button class="edit" name="logOut" value="l">Log Out</button>
    </form>
    <div class="container">
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
                        echo "<br><br><img src='./images/{$products[$i]['image']}'>" ;
                        echo "<span class='title'><b>{$products[$i]['title']}</b></span>";
                        if($products[$i]["expiration_date"] < "2021-01-01"){
                            echo "<span class='title' style='font-style: italic;' >(expired)</span><br>";
                        } else {
                            echo "<span class='title' style='visibility: hidden;'>not expired</span><br>";
                        }
                        echo "<div class='price'>{$products[$i]['normal_price']} TL</div>" ;
                        echo "<div class='price'><b>{$products[$i]['discounted_price']} TL</b></div>" ;
                        echo "<div class='price'>{$products[$i]['expiration_date']}</div>" ;
                        echo "<div class='price'>Stock: {$products[$i]['stock']}</div><br>" ;
                        echo "<div><a class='btn' href='?delete={$products[$i]['title']}'>Delete</a></div><br>" ;
                        echo "<div><a class='btn' href='?update={$products[$i]['title']}'>Update</a></div>" ;
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