<!DOCTYPE html>
<?php
	
	// include this at the top of every file to handle global variables
	// and user log in
	// this will set $user_util class based on local vs production environment
	require_once('utility_functions.php');
	$user_id = $user_util->getCompId();

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
	
	if(empty($user_role)){
		echo "<script>location.href='error.php'</script>";
	}
	
	//get the user's location in the queue
	$position = 1;
	if($stmt -> prepare("SELECT comp_id, location, help FROM active_queue NATURAL JOIN roster ORDER BY enter_ts") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($comp_id, $location, $help);
		while($stmt -> fetch()){
			if($comp_id === $user_id){
				break;
			}else{
				$position++;
			}
		}	
	}
	
	function check_queue(){
		$fd = fopen('queuestatus.txt', 'r');
		$status = fgets($fd);
		fclose($fd);
		return $status;
	}	
	
	if($stmt -> prepare("SELECT COUNT(*) FROM active_queue") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($count);
		$stmt -> fetch();
	}
	

	
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php if(($user_role == 'Instructor' || $user_role == 'TA') && $count > 0) echo "(".$count.") ";?>Office Hours</title>
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
	</head>
	
	<body>
		<!-- navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
				
					<a style="color: white" class="brand" href="#">Office Hours</a>
					
					<ul class="nav pull-right">
						<li><a id="view_cal" class="brand" href="https://www.google.com/calendar/embed?mode=week&src=uvacs1110%40gmail.com&ctz=America/New_York">View Calendar</a></li>
						<li>	
							<button class="btn">
								<strong><?php echo $user_role . ' : ' . $user_fname .' '. $user_lname; ?></strong>
							</button>
						</li>
					</ul>
					<div class="nav-collapse collapse">
							
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<!-- end navbar -->
		
		<div class="container">
			<div class="row-fluid">  
				
				<!-- alert field at top of container -->
				<div id="top-alert" class="alert alert-success" style="display:none;">
					<button id=""></button>
				</div>
				<!-- end alert field -->
				
				<!-- start admin section -->
				<header>
					
					<?php if($user_role == 'Instructor' || $user_role == 'TA') {
						echo "<h1>Hello, " . $user_fname . "!</h1>";
						
						if(check_queue() == "on"){
							$button_status = "Turn OFF Queue";
						}else if(check_queue() == "off"){
							$button_status = "Turn ON Queue";
						}
					?>
					
					<button class="btn btn-primary" id="toggle_btn"><?php echo $button_status ?></button>
					
					<button class='btn btn-danger' id='ta_clear'>Clear Queue</button>
					
				</header>
				
				<div id="student_table">
					<?php
						//set up and display the contents of the queue
						$table = '<table class="table table-striped">
									<thead>
										<th>Name</th>
										<th>Comp ID</th>
										<th>Location</th>
										<th>Help With</th>
										<th>Remove</th>
									</thead>
									<tbody>';
							
						//get users' information
						if($stmt -> prepare("SELECT DISTINCT comp_id, fname, lname, location, help FROM active_queue NATURAL JOIN roster ORDER BY enter_ts") or die(mysqli_error($db))) {
							$stmt -> execute();
							$stmt -> bind_result($comp_id, $fname, $lname, $location, $help);
							while($stmt -> fetch()){
								$table = $table.'<tr><td>'.$fname.' '.$lname.'</td><td>'.$comp_id.'</td><td>'.$location.'</td><td>'. $help .'</td>
								<td><button type="button" class="btn btn-danger ta_remove" name="'.$comp_id.'">×</button></td></tr>';
							}
							$table = $table.'</tbody></table>';
						}
					
						if($stmt -> prepare("SELECT COUNT(*) FROM active_queue") or die(mysqli_error($db))) {
							$stmt -> execute();
							$stmt -> bind_result($queue_size);
							$stmt -> fetch();
						}
					
						if($queue_size > 0){
							echo $table;
						}else if($queue_size == 0 && check_queue() == "on"){
							echo "<div id='empty_table' class='alert'>";
								echo "<strong>EMPTY!</strong> The queue is currently empty. YAY!";
							echo "</div>";
						}else{
							echo "<div id='empty_table' class='alert alert-danger'>";
								echo "<strong>The queue is OFF and no one is currently waiting</strong>";
							echo "</div>";
						}
					?>
				</div> <!-- end student table -->
				
				<?php }else { ?> <!-- start student section -->
					
					<header>
					<!-- fread-->
						<h1>Hello, <?php echo $user_fname; ?>!</h1>
					</header>
					
					<div id="student_info"> <!-- if student isn't already a member of the queue -->
					<?php if($comp_id != $user_id) { 
							if(check_queue() == "off"){		
					?>
								<p>Office Hours are no longer in session. If you were already in the queue we will do our best to help you.</p>
								<p>See the calendar below to see when the next Office Hours session is.</p>
								<iframe src="https://www.google.com/calendar/embed?src=uvacs1110%40gmail.com&ctz=America/New_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
								<script>clearInterval(infoInterval);</script>
					<?php
							}else{
					?>	
								<script>clearInterval(infoInterval);</script>
							<!-- join queue form -->
							<form id="join_queue" name="join_queue">											
								<fieldset>
								<legend>Join Queue</legend>
									<div class="control-group">
										<label class="control-label" for="loc">Your Location in Thornton Stacks:</label><br>
										<div class="controls">
									  		<input type="text" id="loc" name="loc" placeholder="Ex: A1">
										</div>
								 	</div>
								 	<div class="control-group">
										<label class="control-label" for="help">Need help with:</label><br>
										<div style="width: 350px; margin-left: auto; margin-right: auto" class="alert alert-danger">Please be <strong>specific</strong>.  Doing so helps us help you better.  At the very least, enter an Activity Number.  A vague entry will cause you to be <strong>dropped from the queue.</strong></div>
										<div class="controls">
							  				<input type="text" id="help" name="help" placeholder="Ex: Activity 4...">
								  		</div>
								  	</div>
									<div class="control-group">
										<div class="controls">
											<button id="join_btn" type="submit" class="btn btn-success">Join</button>
										</div>
									</div>
								</legend>
							</form>
							
			
							<!-- end join form -->
					<?php 
							}
						} else {
					?>		<!-- if student is already in queue -->
						
						<table>
							<tr>
								<td>Your spot in the queue: <strong><?php echo $position ?></strong></td><td><button type="button" id="student_remove" class="btn btn-danger">Leave Queue</button></td>
							</tr>
							<tr>
								<td>You are at location: <wbr><strong><?php echo $location ?></strong></td><td><button type="button" id="change_loc" class="btn btn-success">Change Location</button></td>	
							</tr>
							<tr>
								<td>You need help with: <wbr><strong><?php echo $help ?></strong></td><td><button type="button" class="btn btn-success" id="change_help">Change Help</button></td>	
							</tr>
						</table>

					 <?php } ?> <!-- end student in/out queue logic -->
					</div>	
							
				<?php } ?> <!-- end of admin/student section -->
				
				<div id="your_location" style="display:none">
					
				</div>
			
				
			</div> <!-- end row div -->
		</div> <!-- end container div -->
		
		<!-- extra divs for modals and such -->
		
		<!-- modal yes/no -->
		<div id="confirm_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="label">Are you sure?</h3>
			</div>
		  	<div class="modal-body">
				<p></p>
		  	</div>
		  	<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-danger" id="remove_student_confirm">Yes</button>
		  	</div>
		</div>
		<!-- end modal yes/no -->
		
		<!-- modal input -->
		<div id="input_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="label">Change</h3>
			</div>
		  	<div class="modal-body">
				<input class="small" id="smallInput" name="smallInput" type="text">		
		 	</div>
		  	<div class="modal-footer">
				
		  	</div>
		</div>
		<!-- end modal input -->
		<footer>
			<hr>
			<small>
				&copy; Hunter Cassidy and Daniel Miller &middot; <a href="terms.html">Terms of Use</a> <?php if($user_role == 'Instructor' || $user_role == 'TA') { ?> &middot; <a href="log.php">Logs</a> &middot; <a href="widget_downloads.html">Widget Downloads</a> &middot; <a href="admin.php">Admin</a><?php } ?>  
			</small>
		</footer>
	</body>	
		
</html>
