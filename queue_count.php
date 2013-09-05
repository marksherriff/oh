<?php

	require_once('dbconnect.php');
	$db = DbUtil::loginConnection();
	$stmt = $db -> stmt_init();
	
	if($stmt -> prepare("SELECT COUNT(*) FROM active_queue") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($count);
		$stmt -> fetch();
	}
	if($count > 0){
		echo "(".$count.") ";
	}
?>