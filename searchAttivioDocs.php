<?php
require_once("/opt/orb-includes/oci_config.php");
//$conn = oci_connect('RPT_USER', 'Qs57Mvd78', $sgcl_rpt);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	  $id = $_GET['id'];
	$qstring = "SELECT DECODE (fir.attachment_status, 1, 'PENDING', 2, 'OCR_COMPLETE', 4, 'Indexed_NOT_Commited', 5, 'OCR_Pending', 6, 'OCR_Indexed_NOT_Commited', 7, 'OCR_ERROR') OCR_File_STATUS, 
	cd.ITEM_NAME AS \"Doc Name\", cd.CREATED_DATE AS \"Doc Created Date\", mf.ORIGINAL_FILE_EXTENSION AS \"File Ext\", MF.PAGE_COUNT AS \"Pagecount\"
	FROM   ma_owner.fts_index_requests fir, ma_owner.ci_document cd, ma_owner.MANAGED_FILE mf
	WHERE  fir.RESOURCE_ID = cd.CONTENT_ID AND cd.content_id = '$id' AND mf.MANAGED_FILE_ID = cd.MANAGED_FILE_ID AND cd.STATUS_ID = 3 ORDER BY fir.attachment_status";
	$stid = oci_parse($conn, $qstring);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
		}
	while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
						{
							$set[] = array('status'=>$row['OCR_File_STATUS'], 'name'=>$row['Doc Name'], 'date'=>$row['Doc Created Date'], 'ext'=>$row['File Ext'], 
							'pagecount'=>$row['Pagecount']);
						}
	echo json_encode($set);
	
?>