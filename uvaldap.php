<?php


#echo 'Connecting...';
$ds = ldap_connect("ldap.virginia.edu");
#echo 'Connect result is ' . $ds . '<br>';

if ($ds) {
	#echo 'Binding...';
	$r = ldap_bind($ds);

	#echo 'Bind result is ' . $r . '<br>';

	#echo 'Searching for (uid=dgm3df)<br>';
	$sr = ldap_search($ds, "o=University of Virginia, c=US", "uid=" . $_POST['param'] ."*");
	#echo 'Search result is ' . $sr . '<br>';

	#echo 'Number of entries returned is ' . ldap_count_entries($ds, $sr) . '<br>';
	if(ldap_count_entries($ds, $sr) == 0){
		echo "No entries found.<hr>";
		die;
	}
	#echo 'Getting entries...<p>';
	$info = ldap_get_entries($ds, $sr);
	#echo 'Data for ' . $info["count"] . ' items returned<p>';

	for ($i=0; $i < $info["count"]; $i++) { 
		echo $info[$i]["cn"][0] . " <br> " . $info[$i]["ou"][0] . "<br>";
		echo $info[$i]["mail"][0];
		if(array_key_exists('mailalternateaddress', $info[$i])){
			echo " / " . $info[$i]["mailalternateaddress"][0] . '<br><br>';
		} else echo '<br><br>';
	}

	#echo "Closing connection";
	//ldap_close($dn);

} else {
	echo "LDAP Connection Failure...<br>";
}

?>