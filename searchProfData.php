<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}		
		$email = $_GET["email"];
		
		$qstring = "SELECT u.email_address,
    u.user_id,
    o.org_name,
    o.organization_id,
    u.last_name,
    u.first_name,
    u.telephone_number,
    u.mobile_phone,
    u.fax_number,
    u.created_date,
    --cc.cc_email,
    u.last_login_success,
    --u.multi_logon_allowed,
    u.password_modified_date,
    u.password_expiration_days - TRUNC(SYSDATE - u.password_modified_date) AS \"PW_EXPIRING_IN\" 
FROM ma_owner.il_user u,
    ma_owner.organization o,
    ma_owner.il_user_cc_email cc
WHERE 1=1
    AND u.email_address = '$email'
    AND u.organization_id = o.organization_id
    AND u.user_id = cc.user_id
    AND u.is_admin_user = 'F'";
		
		//$qstring = "select EMAIL_ADDRESS, USER_ID, ORGANIZATION_ID, LAST_NAME, FIRST_NAME, AUTH_QUESTION, CREATED_DATE, LAST_LOGIN_SUCCESS, PASSWORD_MODIFIED_DATE, PASSWORD_EXPIRATION_DAYS - TRUNC(SYSDATE - PASSWORD_MODIFIED_DATE) AS \"PW EXPIRING IN\" from il_owner.il_user where USERNAME = '$email'";

		$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
			$row_set[] = $row;
			/*$row_set[] = array('firstName'=>$row['first_name'], 'lastName'=>$row['u.last_name'], 'email'=>$row['email_address'], 'userID'=>$row['u.user_id'], 'orgID'=>$row['o.org_name'], 'orgName'=>$row['o.organization_id'],'createdDate'=>$row['u.created_date'],
			'lastLogin'=>$row['u.last_login_success'],'passModDate'=>$row['u.password_modified_date'], 'pwExpIn'=>$row['PW EXPIRING IN'], 'telephone'=>$row['u.telephone_number'],'mobile'=>$row['u.mobile_phone'],
			'fax'=>$row['u.fax_number']);*/
		}
		echo json_encode($row_set);

?>