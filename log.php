<!DOCTYPE html>
<?php
	$user_id = $_SERVER['PHP_AUTH_USER']; //get netbadge 
	//$user_id = 'hwc2d';

	//set up database connection
	require_once("dbconnect.php");
	$db = DbUtil::loginConnection();
	$stmt = $db -> stmt_init();


	//get the user's name from user_id
	if($stmt -> prepare('SELECT fname, lname, role FROM roster WHERE comp_id = ? ORDER BY id DESC') or die(mysqli_error($db))) {
		$stmt -> bind_param("s", $user_id);
		$stmt -> execute();
		$stmt -> bind_result($user_fname, $user_lname, $user_role);
		$stmt -> fetch();
	}
	
	if(empty($user_role) || $user_role == 'Student'){
		echo "<script>location.href='error.php'</script>";
	}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Office Hours</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Office Hours">
		<meta name="author" content="HunterC">
		
		
		<!-- stylesheets -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet"> <!-- responsive bootstrap-->
		
		
		<!-- js -->	
		<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>-->
		<script src="js/jquery-1.8.3.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/script.js"></script>

		<style type="text/css">
		body {
			text-align: left;
		}
		body footer {
			text-align: center;
		}
		</style>
	</head>
	
	<body>
		<!-- navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
				
					<a style="color: white" class="brand" href="index.php">Office Hours</a>
	
				</div>
			</div>
		</div>
		<!-- end navbar -->
		
		<div class="container">
			<h1>Logs</h1><br>
			<legend>U.Va. LDAP Query</legend>
			<div class="clearfix" style="float: left">
          <div class="input"> 
            <input style="text-align: left;" class="xlarge" type="search" id="ldapqry" size="30" maxlen="30" placeholder="Comp ID, then Return"/>
          </div>
          <div id="ldapresult"></div> 
      </div>
      <div style="float: right" id="student_activity_result">
      	Student activity result will show here...
      </div>
			<legend>Student Log</legend>
			<?php 
			$table = '<table class="table table-striped">
									<thead>
										<th>Student</th>
										<th>Location</th>
										<th>Help</th>
										<th>Enter</th>
										<th>Leave</th>
										<th>Reason</th>
									</thead>
									<tbody>';
			$stmt = $db -> stmt_init();
			if ($stmt -> prepare("SELECT * FROM student_logs ORDER BY change_ts DESC") or die(mysqli_error($db))) {
				$stmt -> execute();
				$stmt -> bind_result($student_comp_id, $location, $help, $enter_ts, $leave_ts, $reason);
				$counter = 0;
				while ($stmt -> fetch()) {
					$table = $table.'<tr><td>'.$student_comp_id.'</td><td>'.$location.'</td><td>'.$help.'</td><td>'. $enter_ts .'</td>
								<td>'.$leave_ts.'</td><td>'.$reason.'</td></tr>';
					$counter++;
				}
				$table = $table . '</tbody></table>';
				if ($counter > 0) {
					echo $table;
				} else {
					echo '<div class="alert">Log empty</div>';
				}
				
			}
			 ?>
			<legend>TA Log</legend>
			<?php 
			$table = '<table class="table table-striped">
									<thead>
										<th>Student</th>
										<th>TA</th>
										<th>Help</th>
										<th>Enter</th>
										<th>Leave</th>
										<th>Reason</th>
									</thead>
									<tbody>';
			$stmt = $db -> stmt_init();
			if ($stmt -> prepare("SELECT * FROM ta_logs ORDER BY leave_ts DESC") or die(mysqli_error($db))) {
					$stmt -> execute();
					$stmt -> bind_result($student_comp_id, $ta_comp_id, $help, $enter_ts, $leave_ts, $reason);
					$counter = 0;
					while ($stmt -> fetch()) {
						$table = $table . '<tr><td>' . $student_comp_id . '</td><td>' . $ta_comp_id . '</td><td>' . $help . '</td><td>' . $enter_ts . '</td><td>' . $leave_ts . '</td><td>' . $reason;
						$counter++;
					}
					$table = $table . '</tbody></table>';
					if ($counter > 0) {
						echo $table;
					} else {
						echo '<div class="alert">Log empty</div>';
					}
					
			}
			 ?>
		</div>
		<footer>
			<hr>
			<small>&copy; Hunter Cassidy and Daniel Miller</small>
		</footer>
	</body>	
		
</html>