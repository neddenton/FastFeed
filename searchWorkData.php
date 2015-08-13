<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}		
		$email = $_GET["email"];
		$sorter = $_GET["sorter"];
		if($sorter == "name")
			$sortby = "ExchangeName\" ASC NULLS LAST";
		else if($sorter == "id")
			$sortby = "ID\" ASC NULLS LAST";
		else if($sorter == "accesscount")
			$sortby = "AccessCount\" DESC NULLS LAST";
		else if($sorter == "product")
			$sortby = "Vertical\" ASC NULLS LAST";
		else if($sorter == "phase")
			$sortby = "Phase\" ASC NULLS LAST";
		else if($sorter == "host")
			$sortby = "Host\" ASC NULLS LAST";
		else if($sorter == "role")
			$sortby = "Role\" ASC NULLS LAST";
		else 
			$sortby = "LastAccessed\" DESC NULLS LAST";
		
		$qstring = "SELECT DISTINCT
    w.workspace_name AS \"ExchangeName\",
    w.workspace_id AS \"ID\",
    p.phase_name AS \"Phase\",
    DECODE(w.admin_access,'N','No','Yes') AS \"Support\",
    w.prohibit_multi_logon AS \"ConcurrentLoginDisabled\",
    w.public_private_enabled AS \"PvP\",
    w.pluginless_irm_enabled AS \"PluginlessIRM\",
    w.host AS \"Host\",
    bu.business_unit_name AS \"BusinessGroup\",
    bu.business_unit_id AS \"BGID\",
    v.vertical_name AS \"Vertical\",
    v.vertical_id AS \"VerticalID\",
    r.role_name AS \"Role\",
    DECODE(urr.primary_contact,'Y','Yes',NULL) AS \"KeyContact\",
    LOWER(s.status_desc) AS \"ExchangeStatus\",
    (SELECT TO_CHAR(wla.last_accessed_date, 'YYYY-MM-DD HH24:MI:SS')
        FROM workspace_last_accessed wla 
            WHERE wla.workspace_id = w.workspace_id  
            AND u.user_id = wla.user_id) AS \"LastAccessed\",
    (SELECT wla.number_accessed
        FROM workspace_last_accessed wla 
            WHERE wla.workspace_id = w.workspace_id 
            AND u.user_id = wla.user_id) AS \"AccessCount\"
FROM
    ma_owner.il_user u, 
    il_owner.user_resource_status urs, 
    il_owner.workspace w, 
    il_owner.contact c, 
    il_owner.phase p,
    il_owner.status s,
    il_owner.role r, 
    il_owner.user_resource_role urr, 
    il_owner.resource_role rr, 
    il_owner.business_unit bu,
    il_owner.vertical v 

WHERE w.business_unit_id=bu.business_unit_id
    AND v.vertical_id = w.vertical_id
    AND w.workspace_id = urr.resource_id
    AND r.role_id = rr.role_id
    AND rr.res_role_id = urr.res_role_id
    AND urr.resource_type_id = 9
    AND rr.resource_type_id = 9
    AND u.user_id = urr.user_id
    AND u.user_id = urs.user_id
    AND urs.resource_type_id = 9
    AND urs.resource_id = w.workspace_id
    AND urs.status_id = s.status_id
    AND w.workspace_type_id =  1
    AND c.contact_type_id = 5
    AND c.resource_type_id = 2
    AND c.resource_id = u.user_id
    AND w.phase_id = p.phase_id
    AND lower(u.email_address) LIKE '$email'
ORDER BY \"$sortby";

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
/*$qstring = "SELECT w.workspace_name as \"Name\", w.workspace_id AS \"ID\", 
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
      ORDER BY \"$sortby";*/
?>
