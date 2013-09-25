<?php 
	
	include "databaseconnect.php";



	$result = $mysqli->query("select * from notes");

	$json = json_encode(mysqli_fetch_assoc($result));


	var_dump($result);

	echo $json;

	$json = [];

	while( $note = mysqli_fetch_row($result) )
		{ array_push($json, $note); }

	var_dump( json_encode($json) );

 ?>