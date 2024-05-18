<?php
	session_start();
	require_once "db.php";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		extract($_POST);

		if($id == 1){
			if($getCode == "C"){
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$error = "Invalid Email";
				} else {
					$error = "";
					if( checkUser($email, $pass, $user)){
						$_SESSION["user"] = $user;
						
						require_once "./vendor/autoload.php";
						require_once "Mail.php";

						$randomNumber = mt_rand(100000, 999999);
						$_SESSION["randNum"] = $randomNumber;

						Mail::send($TO, SUBJECT, $randomNumber);

						$error = "Code Sent";
					} else{
						$error = "Wrong Password!!";
					}
				}
			} else if($signin == "S"){
				$randomNumber = $_SESSION["randNum"];
				$user = $_SESSION["user"];
				
				if(intval($code) == filter_var($randomNumber, FILTER_VALIDATE_INT)){
					$_SESSION["randNum"] = NULL;
					if($user["type"] == "C"){
						$_SESSION["titles"] = array();
						$_SESSION["quantities"] = array();
						header("Location: consumer.php");
					}
					else if($user["type"] == "M"){
						header("Location: market.php");
					} else {
						$error = "Please Enter Your Information!!";
					}
				} else {
					$error = "Wrong Code Entered. Sign in again!";
				}
				
			}	
		} else {
			$hash = password_hash($pass, PASSWORD_BCRYPT);
			$sql = "insert into users (email, password, name, city, district, address, type) values (?, ?, ?, ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([filter_var($email, FILTER_VALIDATE_EMAIL), $hash, filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($city, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($district, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($type, FILTER_SANITIZE_FULL_SPECIAL_CHARS)]);
		}
		
	}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Up Page</title>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="signIn.css">
</head>
<body>
    <h2>Welcome To CTIS256 Project</h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<div id="titleDiv">
			<h1 id="createTitle">Create Consumer</h1>
			<button id="switchBtn">Switch User Type</button>
		</div>
		<form action="#" method="post"> 
			<input type="text" placeholder="Email" name="email" value = "<?= $error == "" ? '' : $email?>"/>
			<input type="password" placeholder="Password" name="pass"/>
			<input id="nameType" type="text" placeholder="Consumer Name" name = "name" value = "<?= $error == "" ? '' : $name?>"/>
			<input type="text" placeholder="City" name="city" value = "<?= $error == "" ? '' : $city?>"/>
			<input type="text" placeholder="District" name ="district" value = "<?= $error == "" ? '' : $district?>"/>
			<input type="text" placeholder="Address" name = "address" value = "<?= $error == "" ? '' : $address?>"/>
			<input type="hidden" name="id" value="2">
			<input type="hidden" name="type" value="C" id="type">
			<button>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="#" method="post">
			<h1 id="signin">Sign in</h1>
			<input type="text" placeholder="Email" name = "email" value = "<?= $error == "" ? '' : $email?>"/>
			<input type="password" placeholder="Password" name = "pass" />
			<button name="getCode" value="C">Get Code</button>
			<input type="text" placeholder="6-digit code" name = "code" />
			<input type="hidden" name="id" value="1">
			<button name="signin" value="S">Sign In</button>
			<?php
				if($error != ""){
					echo "<span class='error_msg' style='color: red; font-weight: bold; padding-top: 10px;'>$error</span>";
				}
			?>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>Already Have an Account?</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, User!</h1>
				<p>Please Enter Your Information If You Don't Have an Account</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
</body>
</html>

<script src="signIn.js"></script>