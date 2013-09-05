<?php 
	$fd = fopen('queuestatus.txt', 'r');
	$status = fgets($fd);
	fclose($fd);
	if($status == "on") {
		$fd = fopen('queuestatus.txt', 'w');
		fwrite($fd, "off");
		fclose($fd);
		echo "off";
	} else if ($status == "off") {
		$fd = fopen('queuestatus.txt', 'w');
		fwrite($fd, "on");
		fclose($fd);
		echo "on";
	}
 ?>