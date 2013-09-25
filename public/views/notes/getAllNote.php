<?php 
	
	include "database.php";



	$result = $mysqli->query("select * from notes");

	$json = json_encode(mysqli_fetch_assoc($result));
	
	echo $json;

 ?>