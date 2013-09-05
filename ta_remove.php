<?php
  $ta_comp_id = $_SERVER['PHP_AUTH_USER'];
  //$ta_comp_id = 'hwc2d';
  
  $user_comp_id = strip_tags($_GET['id']);
  
  require_once('dbconnect.php');

  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();



  $reason = 'ta_removed';

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
  
  if($stmt -> prepare("INSERT INTO ta_logs (`student_comp_id`, `ta_comp_id`, `help`, `enter_ts`, `reason`) VALUES (?, ?, ?, ?, ?)") or die(mysqli_error($db))) {
    $stmt -> bind_param("sssss", $user_comp_id, $ta_comp_id, $help, $enter_ts, $reason);
    $stmt -> execute();
  }

  $stmt -> close();
  $db -> close();
  
  
  echo '<button id="close_alert" class="close">×</button>';
  echo 'Successfully removed <strong>' . $user_comp_id . '</strong> from queue.';
?>