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
		<style type="text/css">
		body {
			text-align: left;
		}
		body footer {
			text-align: center;
		}
		</style>
				
		<!-- js -->	
		<script src="js/jquery-1.8.3.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/script.js"></script>
		<script src="js/bootstrap.file-input.js"></script>
		<script>
		$(document).ready(function(){
			$('#truncate').click(function({
				var really = prompt("This is permanent.  If you are sure, please type YES below");
				if(really != null && person != "" && person == "YES") {
					$.ajax({
						method: "POST",
						url: "truncate.php",
						success: function(data) {
							alert("Truncation success.  I hope you meant to do that.");
						}
					});
				} else {
					alert("Incorrect validation");
				}
			});
		});
		</script>

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
			<h1>Admin Page</h1><br>
			<legend>Upload Roster File as CSV</legend>
			<p>
				Instructions for uploading a new roster:
				<ul>
					<li>The CSV must be of the format: comp_id, lname, fname, role</li>
					<li>Role must be one of: Student, Professor, TA</li>
					<li>Choose the file below, and select upload</li>
				</ul>
			</p>
			<p>
				<form enctype="multipart/form-data" action="upload_roster.php" method="post">
					<input name="filename" type="file"><br><br>
					<input type="submit" class="btn">
				</form>
			</p>
			<legend>Truncate Roster Table</legend>
			<p>This button will clear the roster table.</p>
			<button class="btn btn-danger" id="truncate">DANGER - THIS ACTION IS IRREVERSIBLE</button>
		</div>
		<footer>
			<hr>
			<small>&copy; Hunter Cassidy and Daniel Miller</small>
		</footer>
	</body>	
		
</html>