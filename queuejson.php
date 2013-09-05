<?php
	require_once('dbconnect.php');

	$db = DbUtil::loginConnection();
	$stmt = $db -> stmt_init();
	$json = array();
	$masterjson = array();

	if($stmt -> prepare("SELECT comp_id, fname, lname, location, help FROM active_queue NATURAL JOIN roster ORDER BY enter_ts") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($comp_id, $fname, $lname, $location, $help);
		while($stmt -> fetch()){
			$json['comp_id'] = $comp_id;
			$json['fname'] = $fname;
			$json['lname'] = $lname;
			$json['location'] = $location;
			$json['help'] = $help;
			$masterjson[] = $json;
			$json = array();
		}

		echo json_encode($masterjson);
	}
?>