<?php
    session_start();
    require "db.php";

	if(!isset($_SESSION["user"])){
		$_SESSION["user"] = NULL;
        header("Location: signIn.php");
        exit;
    }

    $user = $_SESSION["user"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
		if($update == "u"){
			if($newPass == ""){
				$stmt = $db->prepare("update users set name = ?, city = ?, district = ?, address = ? where email = ?");
				$stmt->execute([filter_var($newName, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($newCity, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($newDistrict, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($newAddress, FILTER_SANITIZE_FULL_SPECIAL_CHARS), $user["email"]]);
			} else {
				$hash = password_hash($newPass, PASSWORD_BCRYPT);
				$stmt = $db->prepare("update users set name = ?, city = ?, password = ?, district = ?, address = ? where email = ?");
				$stmt->execute([filter_var($newName, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($newCity, FILTER_SANITIZE_FULL_SPECIAL_CHARS), $hash, filter_var($newDistrict, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($newAddress, FILTER_SANITIZE_FULL_SPECIAL_CHARS), $user["email"]]);
				$_SESSION["titles"] = array();
				$_SESSION["user"] = NULL;
				header("Location: signIn.php");
				exit;
			}
		}
		$user = getUser($user["email"]);
		$_SESSION["user"] = $user;
		if($goback == "g"){
			if($user["type"] == "C"){
				header("Location: consumer.php");
			}
			else{
				header("Location: market.php");
			}
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
    <title>Edit Profile Page</title>
</head>
<body>
<h2>My Profile</h2>

<div class="container">
		<form action="" method="post">
			<div class="title">
				<h2>User Information</h2>
			</div>
			<div class="infoBox">
				<div class="info">
					<label class="form-label">Username *</label>
					<input type="text" class="form-control" name="newName" value="<?= $user['name']?>">
				</div>
				
				<div class="info">
					<label class="form-label">Address *</label>
					<input type="text" class="form-control" name="newAddress" value="<?= $user['address']?>">
				</div>
				
				<div class="info">
					<label class="form-label">District *</label>
					<input type="text" class="form-control" name="newDistrict" value="<?= $user['district']?>">
				</div>
				
				<div class="info">
					<label class="form-label">Email *</label>
					<input type="email" class="form-control" name="newEmail" value="<?= $user['email']?>" readonly>
				</div>
				
				<div class="info">
					<label class="form-label">City *</label>
					<input type="text" class="form-control" name="newCity" value="<?= $user['city']?>">
				</div>
				
				<div class="info">
					<label class="form-label">New Password *</label>
					<input type="password" class="form-control" name="newPass">
				</div>
				<div class="btnBox">
					<button class="btn" name="update" value="u">Update / Save Profile</button>
					<button class="btn" name="goback" value="g">Go Back</button>
				</div>       
			</div>
		</form>
	</div>
</body>
</html>