<?php
	$user_comp_id = $_SERVER['PHP_AUTH_USER'];
	//$user_comp_id =  'hwc2d';
	
	$location = strip_tags($_GET['loc']);
	$help = strip_tags($_GET['help']);
	
	require_once('dbconnect.php');
	$db = DbUtil::loginConnection();
	$stmt = $db -> stmt_init();
	
	
	if($stmt -> prepare("INSERT INTO active_queue (`comp_id`, `location`, `help`) VALUES (?, ?, ?)") or die(mysqli_error($db))) {
		$stmt -> bind_param("sss", $user_comp_id, $location, $help);
		$stmt -> execute();
	}
	
	//get the user's info
	if($stmt -> prepare('SELECT enter_ts FROM active_queue WHERE comp_id = ?') or die(mysqli_error($db))) {
	  $stmt -> bind_param("s", $user_comp_id);
	  $stmt -> execute();
	  $stmt -> bind_result($enter_ts);
	  $stmt -> fetch();
	}
	
	if($stmt -> prepare("SELECT COUNT(*) FROM active_queue") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($position);
		$stmt -> fetch();
	}
	
	
	echo '<table>';
	echo '<tr><td>Your spot in the queue: <strong>' . $position . '</strong></td><td><button type="button" class="btn btn-danger" id="student_remove">Leave Queue</button></td></tr>';
	echo '<tr><td>You are at location: <strong>' . $location . '</strong></td><td><button type="button" class="btn btn-success" id="change_loc">Change Location</button></td></tr>';
	echo '<tr><td>You need help with: <strong>' . $help . '</strong></td><td><button type="button" class="btn btn-success" id="change_help">Change Help</button></td></tr>';
	echo '</table>';

?>