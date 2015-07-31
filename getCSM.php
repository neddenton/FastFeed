<?php
	$con=mysqli_connect("localhost","edenton","646S5mShzvvJNb7c", "edenton");
	$email = $_GET['email'];	
	$qstring = "SELECT INSTRUCTION FROM Instructions WHERE EMAIL = '$email'";
	$result = mysqli_query($con, $qstring);
	if($row  = mysqli_fetch_assoc($result)){
		$row_set[] = $row;					
	}
	echo json_encode($row_set);
	
?>