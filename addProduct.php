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

    $user = $_SESSION["user"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
		
		if($goback == "g"){
			if($user["type"] == "C"){
				header("Location: consumer.php");
			}
			else{
				header("Location: market.php");
			}
		}

		if($expiration_date < "2022-01-01" && $expiration_date >="2021-01-01"){
			foreach($_FILES as $fb => $file) {
				move_uploaded_file($file["tmp_name"], "./images/" . $file["name"]) ;
			}

			$stmt = $db->prepare("insert into products (title, stock, normal_price, discounted_price, expiration_date, image, city, district) values(?,?,?,?,?,?,?,?)");
        	$stmt->execute([filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($stock, FILTER_VALIDATE_INT), filter_var($normal_price, FILTER_VALIDATE_FLOAT), filter_var($discounted_price, FILTER_VALIDATE_FLOAT), filter_var($expiration_date, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($file["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS), $user["city"], $user["district"]]);
		} else {
			$error = "Invalid Expiration Date!!";
		}
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel="stylesheet" href="./editProfile.css">
    <title>Add Product Page</title>
</head>
<body>
	<h2>Add Product</h2>
	<div class="container">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="title">
				<h2>Product Information</h2>
			</div>

			<div class="infoBox">
				<div class="info">
					<label class="form-label">Product Name *</label>
					<input type="text" class="form-control" name="title" value="<?= $product['name']?>">
				</div>
				<div class="info">
					<label class="form-label">Stock *</label>
					<input type="text" class="form-control" name="stock" value="<?= $product['stock']?>">
				</div>
				<div class="info">
					<label class="form-label">Normal Price *</label>
					<input type="text" class="form-control" name="normal_price" value="<?= $product['normal_price']?>">
				</div>
				<div class="info">
					<label class="form-label">Discounted Price *</label>
					<input type="text" class="form-control" name="discounted_price" value="<?= $product['discounted_price']?>">
				</div>
				<div class="info">
					<label class="form-label">Expiration Date *</label>
					<input type="text" class="form-control" name="expiration_date" value="<?= $product['expiration_date']?>">
				</div>
				<div class="btnBox">
					<input class="btn" type="file" name="upload" value="u">
					<button class="btn" name="add" value="a">Add / Save Product</button>
					<button class="btn" name="goback" value="g">Go Back</button>
				</div>
				<div class="error">
					<?php
						if(isset($error)){
							echo "<p>$error</p>";
						}
					?>
				</div>
			</div>
		</form>
	</div>
</body>
</html>