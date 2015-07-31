<?php				
			$con=mysqli_connect("localhost","iwr","RCwZms97ZhNdt7qX", "iwr");

	if (mysqli_connect_errno())
	  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	$query = "select small,medium,large from `iwr`.`attivioqueueocr` order by timestamp desc limit 1";

	$result = mysqli_query($con,$query);

	while($row    = mysqli_fetch_assoc($result))

						{
							$set[] = $row;
						}
	
	
	
	$ocrsmall =  number_format($set[0]['small']);
	
	$ocrmedium=  number_format($set[0]['medium']);
	
	$ocrlarge =  number_format($set[0]['large']);
	
	
	$query2 = "select small,medium,large from `iwr`.`attivioqueuepdf` order by timestamp desc limit 1";

	$result2 = mysqli_query($con,$query2);

	while($row2    = mysqli_fetch_assoc($result2))

						{
							$set2[] = $row2;
						}
						
	$pdfsmall =  number_format($set2[0]['small']);
	
	$pdfmedium=  number_format($set2[0]['medium']);
	
	$pdflarge =  number_format($set2[0]['large']);
			

	$return = array('ocrsmall'=>$ocrsmall, 'ocrmedium'=>$ocrmedium, 'ocrlarge'=>$ocrlarge,'pdfsmall'=>$pdfsmall, 'pdfmedium'=>$pdfmedium, 'pdflarge'=>$pdflarge);
	echo(json_encode($return));
	mysqli_close($con);
	

	
	header("refresh:60;url=.");
?>