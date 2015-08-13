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
    (SELECT TO_CHAR(u.created_date, 'YYYY-MM-DD HH24:MI:SS') FROM ma_owner.il_user u WHERE u.email_address = '$email') AS \"CREATED_DATE\",
    (SELECT RTRIM (
                  XMLAGG (XMLELEMENT (e, cc.cc_email || ',')).EXTRACT (
                     '//text()'),
                  ',')
                  enames
          FROM ma_owner.il_user_cc_email cc
         WHERE u.user_id = cc.user_id
            AND cc.cc_email <> 'none') AS \"CC_Email\",
    (SELECT TO_CHAR(u.last_login_success, 'YYYY-MM-DD HH24:MI:SS') FROM ma_owner.il_user u WHERE u.email_address = '$email') AS \"LAST_LOGIN_SUCCESS\",
    (SELECT TO_CHAR(u.password_modified_date, 'YYYY-MM-DD HH24:MI:SS')FROM ma_owner.il_user u WHERE u.email_address = '$email') AS \"PASSWORD_MODIFIED_DATE\",
    u.password_expiration_days - TRUNC(SYSDATE - u.password_modified_date) AS \"PW_EXPIRING_IN\" 
FROM ma_owner.il_user u,
    ma_owner.organization o
WHERE 1=1
    AND u.email_address = '$email'
    AND u.organization_id = o.organization_id
    AND u.is_admin_user = 'F'";
		
		

		$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
			$row_set[] = $row;
		}
		echo json_encode($row_set);

?>