<?php
session_start();
if(true){				//$_SESSION["loggedIn"] ==
	$con=mysqli_connect("localhost","edenton","646S5mShzvvJNb7c", "edenton");
	$thisTop = $_POST["thisTop"];
	$thisLeft = $_POST["thisLeft"];
	$thisZ = $_POST["thisZ"];
	$i = $_POST["iterant"];
	$id = $_POST["id"];
	$idCheckStr = "SELECT TOP_1 FROM positioning WHERE ID = '".$id."'";
	$idCheck = mysqli_query($con, $idCheckStr);
	if($con->connect_error){
		error_log("connect error for local mySQL table");
	}
	if(sizeOf(mysqli_fetch_assoc($idCheck)) > 0){
		$writetosql = "UPDATE positioning SET TOP_".$i."='".$thisTop."', LEFT_".$i."='".$thisLeft."', Z_".$i."='".$thisZ."' WHERE ID='".$id."'";
	}
	else {
		$writetosql = "INSERT INTO positioning (TOP_".$i.", LEFT_".$i.", Z_".$i.", ID) VALUES ('".$thisTop."', '".$thisLeft."', '".$thisZ."', '".$id."')";
	}
	
	$con->query($writetosql);
}


?>