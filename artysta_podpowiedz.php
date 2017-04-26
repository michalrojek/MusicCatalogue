<?php
	require_once('zalogowano.php');
	
	$term = $_GET['term'];

	$data = $link->query("select artysta.imie_artysty as autor from artysta");
	
		while($row=$data->fetch_assoc())
		{
			$autorzy[]=$row['autor'];
		}
	
	
	echo json_encode($autorzy);

?>