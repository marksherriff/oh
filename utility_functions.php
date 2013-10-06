<?php
	// need to include this at the top of every file to get global settings.
	require_once('config/config.php');


	// need special function to handle local vs production environment
	$user_util = new UserUtil($useNetbadge);
	
	
	class UserUtil {

		public $useNetbadge = true;

		public function __construct($netbadge) {
			$this->useNetbadge = $netbadge;
		}

		// gets the compid of the user. If local development, grabs it from
		// either session variable or from url parameters. If production,
		// uses netbadge.
		// url parameters of form: compid=kmw8sf
		public function getCompId() {
			// Netbadge does not work in local development
			if ($this->useNetbadge) {
				//production environment
				return $_SERVER['PHP_AUTH_USER']; //get netbadge
			} else {
				// local development
				if (!isset($_SESSION)) {
					// only start the session if it isn't already started
					session_start();
				}
				if (isset($_GET['compid'])) {
					// If url parameter provided, overwrite current session (if exists)
					$_SESSION['compId'] = $_GET['compid'];
					return $_SESSION['compId'];
				} else if (isset($_SESSION['compId'])) {
					// Session already created, grab it
					return $_SESSION['compId'];
				} else {
					// if they forgot to include a url parameter, print this out to help
					echo "Please include compid={compid} in url to start local session";
					die();
				}
			}
		}
	}



?>