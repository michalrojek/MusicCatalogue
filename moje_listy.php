<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src=https://code.jquery.com/jquery-3.1.1.js type="text/javascript"></script>
</head>
<body>
    <?php
        require_once('menu.php');
    ?>
	<h2 class="heading-centre">Moje listy</h2>
       <div class="search-form">
            <div class="search-from-child">
	<a href="lista_dodaj.php">Stwórz nową listę</a><br>
           </div></div>
	<?php
		
		$q1=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}'");
		echo "<br>";
		while($row = $q1->fetch_assoc()) 
		{
			echo '<div class="container"><b>Nazwa listy: </b><a href=edytuj_liste.php?id_l='.$row["id_listy"].">".$row["nazwa_listy"]."</a><br>
            <b>Usuwanie listy: </b><a href=usun_liste.php?id_l=".$row["id_listy"].">Usuń listę</a></div>";
		}
	
		$link->close();
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>