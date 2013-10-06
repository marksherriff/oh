<?php

  // include this at the top of every file to handle global variables
  // and user log in
  // this will set $user_util class based on local vs production environment
  require_once('utility_functions.php');
  $user_comp_id = $user_util->getCompId();
  
  $help = strip_tags($_GET['help']);
  require_once('dbconnect.php');

  $db = DbUtil::loginConnection();
  $stmt = $db -> stmt_init();


  $reason = 'changed_help';
  
  //get the user's info
  if($stmt -> prepare('SELECT location, enter_ts FROM active_queue WHERE comp_id = ?') or die(mysqli_error($db))) {
    $stmt -> bind_param("s", $user_comp_id);
    $stmt -> execute();
    $stmt -> bind_result($location, $enter_ts);
    $stmt -> fetch();
  }

  if($stmt -> prepare('UPDATE active_queue SET help = ? WHERE comp_id = ?') or die (mysqli_error($db))) {
    $stmt -> bind_param("ss", $help, $user_comp_id);
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
  echo 'Successfully changed <strong>help</strong>.';

?>
