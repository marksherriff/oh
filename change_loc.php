<?php
  $user_comp_id = $_SERVER['PHP_AUTH_USER'];
  //$user_comp_id = 'hwc2d';
  
  $loc = strip_tags($_GET['loc']);
  
  require_once('dbconnect.php');

  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();

  
  $reason = 'changed_loc';

  //get the user's info
  if($stmt -> prepare('SELECT help, enter_ts FROM active_queue WHERE comp_id = ?') or die(mysqli_error($db))) {
    $stmt -> bind_param("s", $user_comp_id);
    $stmt -> execute();
    $stmt -> bind_result($help, $enter_ts);
    $stmt -> fetch();
  }

  if($stmt -> prepare('UPDATE active_queue SET location = ? WHERE comp_id = ?') or die (mysqli_error($db))) {
    $stmt -> bind_param("ss", $loc, $user_comp_id);
    $stmt -> execute();
    $db -> commit();
  }
  
  if($stmt -> prepare("INSERT INTO student_logs (`student_comp_id`, `location`, `help`, `enter_ts`, `reason`) VALUES (?, ?, ?, ?, ?)") or die(mysqli_error($db))) {
    $stmt -> bind_param("sssss", $user_comp_id, $loc, $help, $enter_ts, $reason);
    $stmt -> execute();
  }

  $stmt -> close();
  $db -> close();
  
  echo '<button id="close_alert" class="close">×</button>';
  echo 'Successfully changed location to <strong>' . $loc . '</strong>.';

?>

