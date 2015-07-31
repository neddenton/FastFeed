<?php
	$con=mysqli_connect("localhost","edenton","646S5mShzvvJNb7c", "edenton");
	$orgid = $_GET['orgid'];	
	$qstring = "SELECT CSM, SALES FROM contact WHERE ORG_ID = '$orgid'";
	$result = mysqli_query($con, $qstring);
	if($row  = mysqli_fetch_assoc($result)){
		$row_set[] = $row;					
	}
	echo json_encode($row_set);
	
?>