<?php	
	 // random result name generator

        $characters = 'abcdef0123456789';
        $generatedhash = '';
                for ($i = 0; $i < 16 ; $i++) {
                $generatedhash .= $characters[rand(0, strlen($characters) - 1)];
                }

	// declare timestamp + hash for filename, location
	
        $timestamp = date('YmdHis');
        $filename = "$timestamp-$generatedhash.csv";
        $splunk_csv = "/tmp/$timestamp-$generatedhash-00.csv";
        $dirloc = "/tmp/";
        $fileloc = $dirloc . $filename;
	
	$spurl = " 'https://wooddale.splunk.intralinks.com:8089/servicesNS/blipke@intralinks.com/search/search/jobs/export' ";
	
	// creds
	
	$cr = "blipke@intralinks.com:M4serat\! ";
	
	//$uid is the USER_ID value that we pull from Oracle in IL_OWNER.IL_USER.USER_ID
	$param = 'POST /services/client HTTP/1.1 200 https://services.intralinks.com/login/' ;
	$uid = $_GET['id'];
	$call_splunk = "curl -k -u $cr --data-urlencode search=\"search sourcetype=Apache2 $param $uid | head 1 \" -d \"output_mode=json\" $spurl  > $fileloc";
	//$call_splunk = "curl -k -u $cr --data-urlencode search=\"search sourcetype=Apache2 $uid | head 1 \" -d \"output_mode=json\" $spurl  > $fileloc";

	exec($call_splunk);
	
	$json = file_get_contents($fileloc);
	error_log($json);
	echo($json);
		
?>