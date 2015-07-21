<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}		
		$email = $_GET["email"];
		$qstring = "SELECT w.workspace_name as \"Name\", w.workspace_id AS \"ID\", 
       u.username AS \"Email\", 
       v.vertical_id AS \"Product Code\",
       v.vertical_name AS \"Product Name\",
     --u.user_id as \"User ID\", 
       wla.number_accessed AS \"Access Count\", 
       wla.last_accessed_date AS \"Last Accessed\" 
  FROM il_owner.il_user u, 
       il_owner.user_resource_status urs, 
       il_owner.workspace w, 
       il_owner.phase, 
       il_owner.status s1, 
       il_owner.status s2, 
       il_owner.workspace_last_accessed wla, 
       il_owner.vertical v
WHERE     wla.workspace_id = w.workspace_id 
       AND w.vertical_id = v.vertical_id
       AND wla.user_id = u.user_id 
       AND u.user_id = urs.user_id
       AND urs.resource_type_id = 9 
       AND urs.resource_id = w.workspace_id 
       AND urs.status_id = s2.status_id 
       AND s2.status_desc <> 'DELETED' -- No deleted users 
       AND u.system_status_id = s1.status_id 
       AND s1.status_id <> 5 -- No deregistered users 
       AND w.phase_id = phase.phase_id 
       AND w.phase_id <> 4 
       AND w.workspace_type_id = 1 
       AND u.username = '$email'
       --AND w.arc_enabled = 'N' -- IL5 exchanges
      ORDER BY 4";
;

		$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
			$row_set[] = array('name'=>$row['Name'], 'code'=>$row['Product Code'], 'id'=>$row['ID'], 'prodName'=>$row['Product Name'], 'userID'=>$row['User ID'], 'accessCount'=>$row['Access Count'],
			'lastAccessed'=>$row['Last Accessed']);
		}
		echo json_encode($row_set);

?>