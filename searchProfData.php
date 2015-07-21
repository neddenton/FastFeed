<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}		
		$email = $_GET["email"];
		$qstring = "select EMAIL_ADDRESS, USER_ID, ORGANIZATION_ID, LAST_NAME, FIRST_NAME, AUTH_QUESTION, CREATED_DATE, LAST_LOGIN_SUCCESS, PASSWORD_MODIFIED_DATE, PASSWORD_EXPIRATION_DAYS - TRUNC(SYSDATE - PASSWORD_MODIFIED_DATE) AS \"PW EXPIRING IN\" from il_owner.il_user where USERNAME = '$email'";

		$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
			$row_set[] = array('firstName'=>$row['FIRST_NAME'], 'lastName'=>$row['LAST_NAME'], 'email'=>$row['EMAIL_ADDRESS'], 'userID'=>$row['USER_ID'], 'orgID'=>$row['ORGANIZATION_ID'],'authQuestion'=>$row['AUTH_QUESTION'],'createdDate'=>$row['CREATED_DATE'],
			'lastLogin'=>$row['LAST_LOGIN_SUCCESS'],'passModDate'=>$row['PASSWORD_MODIFIED_DATE'], 'pwExpIn'=>$row['PW EXPIRING IN']);
		}
		echo json_encode($row_set);

?>