<?php 

require_once('dbconnect.php');

$db = DbUtil::loginConnection();
$stmt = $db -> stmt_init();

echo "<strong>Queue activity for " . $_POST['param'] . "</strong><br><hr>";

if ($stmt -> prepare("SELECT location, help, enter_ts, change_ts, reason FROM student_logs WHERE student_comp_id = ?") or die(mysqli_error($db))) {
	$stmt -> bind_param("s", $_POST['param']);
	$stmt -> execute();
	$stmt -> bind_result($loc, $help, $enter, $leave, $reason);
	while ($stmt -> fetch()) {
		echo $loc . ' - ' . $help . ' - ' . $enter . ' - ' . $leave . ' - ' . $reason . '<br>';
	}
}

 ?>