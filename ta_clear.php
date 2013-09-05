<?php
  $comp_id = $_SERVER['PHP_AUTH_USER'];
  require_once('dbconnect.php');
  
  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();

  if($stmt -> prepare('SELECT role FROM roster WHERE comp_id = ?') or die(mysqli_error($db))) {
  	$stmt -> bind_param("s", $comp_id);
		$stmt -> execute();
		$stmt -> bind_result($user_role);
		$stmt -> fetch();
	}
  
  if($user_role == 'Instructor' || $user_role == 'TA'){
  
  if($stmt -> prepare('DELETE FROM active_queue') or die (mysqli_error($db))) {
    $stmt -> execute();
    $db -> commit();
  }

  $stmt -> close();
  $db -> close();


  echo '<button id="close_alert" class="close">×</button>';
  echo 'Successfully removed all students from queue.';
  } else {
    header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found', true, 404);
    exit;
  }
?>
