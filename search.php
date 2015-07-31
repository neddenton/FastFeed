<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}		
		$term = $_GET['term'];
		if(stripos($term, " ") === FALSE){
			$term = trim($_GET['term']);
			$qstring = "SELECT EMAIL_ADDRESS,FIRST_NAME,LAST_NAME, USER_ID FROM il_owner.il_user WHERE lower(EMAIL_ADDRESS) LIKE lower('%$term%') ORDER BY FIRST_NAME ASC";
		}
		else{
			$term = preg_replace('/\s+/', '', $_GET['term']);
			$qstring = "SELECT EMAIL_ADDRESS,FIRST_NAME,LAST_NAME FROM il_owner.il_user WHERE lower(CONCAT(REGEXP_REPLACE(FIRST_NAME, '\s*', ''),REGEXP_REPLACE(LAST_NAME, '\s*', ''))) LIKE lower('%$term%') ORDER BY FIRST_NAME ASC";
		}
		$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
			$row['value']=stripslashes($row['FIRST_NAME']." ".$row['LAST_NAME']."    ".$row['EMAIL_ADDRESS']);
			$row['FIRST_NAME']=htmlentities(stripslashes($row['FIRST_NAME']));
			$row['EMAIL_ADDRESS']=htmlentities(stripslashes($row['EMAIL_ADDRESS']));
			$row['LAST_NAME']=htmlentities(stripslashes($row['LAST_NAME']));
			$row_set[] = array('value'=>$row['value'],'label'=>$row['value'],'firstName'=>$row['FIRST_NAME'],'email'=>$row['EMAIL_ADDRESS'],'lastName'=>$row['LAST_NAME'], 'id'=>$row['USER_ID']);
		}
		echo json_encode($row_set);
?>
