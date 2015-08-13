<?php
				session_start();
			if(true){				//$_SESSION["loggedIn"] ==
				$con=mysqli_connect("localhost","edenton","646S5mShzvvJNb7c", "edenton");
				$email = $_POST["email"];
				$inst = $_POST["instruction"];
				error_log("instruuuuuuct".$inst.$email);
				if($con->connect_error){
					error_log("connect error for local mySQL table");
				}
				$idCheckStr = "SELECT INSTRUCTION FROM instructions WHERE EMAIL = '".$email."'";
				$idCheck = mysqli_query($con, $idCheckStr);
				error_log($idCheckStr);
				if(sizeOf(mysqli_fetch_assoc($idCheck)) > 0){
		$writetosql = "UPDATE instructions SET INSTRUCTION = '".$inst."'  WHERE EMAIL = '".$email."'";
	}
	else {
		$writetosql = "INSERT INTO instructions (INSTRUCTION, EMAIL) VALUES ('".$inst."', '".$email."')";
	}
				
				
					error_log($writetosql);
					$con->query($writetosql);
				} 
?>