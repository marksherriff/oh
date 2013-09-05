<?php
  $user_comp_id = $_SERVER['PHP_AUTH_USER'];
  //$user_comp_id = 'hwc2d';

  require_once('dbconnect.php');

  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();

  $reason = 'removed_self';

  //get the user's info
  if($stmt -> prepare('SELECT location, help, enter_ts FROM active_queue WHERE comp_id = ?') or die(mysqli_error($db))) {
    $stmt -> bind_param("s", $user_comp_id);
    $stmt -> execute();
    $stmt -> bind_result($location, $help, $enter_ts);
    $stmt -> fetch();
  }
  
  if($stmt -> prepare('DELETE FROM active_queue WHERE comp_id = ?') or die (mysqli_error($db))) {
		$stmt -> bind_param("s", $user_comp_id);
		$stmt -> execute();
		$db -> commit();
  }
  
  if($stmt -> prepare("INSERT INTO student_logs (`student_comp_id`, `location`, `help`, `enter_ts`, `reason`) VALUES (?, ?, ?, ?, ?)") or die(mysqli_error($db))) {
    $stmt -> bind_param("sssss", $user_comp_id, $location, $help, $enter_ts, $reason);
    $stmt -> execute();
  }
  
 
  

  $stmt -> close();
  $db -> close();
  
  echo '<button id="close_alert" class="close">×</button>';
  echo 'You have been successfully removed from the queue.';

?>