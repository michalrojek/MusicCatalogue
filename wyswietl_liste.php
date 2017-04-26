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
	<?php
		
		if(isset($_GET['id_l']))
		{
			$id_l=$_GET['id_l'];
			$q1=$link->query("select * from lista where id_listy='$id_l'");
			$row1=$q1->fetch_assoc();
			$id_u=$row1['id_uzytkownika'];
			$lista_dane=$q1->fetch_assoc();
			
			$q2=$link->query("select * from album_to_lista where id_listy='$id_l'");
			
			$q3=$link->query("select login from uzytkownik where id_uzytkownika='$id_u'");
			$row2=$q3->fetch_assoc();
			echo '<h2 class="heading-centre">'.$row1['nazwa_listy'].'</h2>';
			
			echo '<br><div class="container">Lista utwożona przez użytkownika <b>'.$row2['login']."</b><br>";
			
			echo "<br>";
			
			echo "<b>Opis: </b></br>".$row1['opis_listy']."</br><br>";
			$albumy=[];
			$i=0;
			while($row = $q2->fetch_assoc()) 
			{
				$q3=$link->query("select * from album where id_albumu='{$row['id_albumu']}'");
				$albumy[$i]=$q3->fetch_assoc();
				$i++;
			}
            
            echo '<b>Zawartość listy:</b><br>';
			foreach($albumy as $item)
			{
				$q4=$link->query("select * from album_to_artysta where id_albumu='{$item['id_albumu']}'");
				$row4=$q4->fetch_assoc();
				if(count($row4)==0)
				{
					$q4=$link->query("select * from album_to_zespol where id_albumu='{$item['id_albumu']}'");
					$row4=$q4->fetch_assoc();
					$q5=$link->query("select * from zespol where id_zespolu='{$row4['id_zespolu']}'");
					$row5=$q5->fetch_assoc();
					echo "<a href=zespol_wyswietl.php?id_z=".$row5['id_zespolu'].">".$row5['nazwa_zespolu']."</a> - <a href=album_wyswietl.php?id_a=".$item['id_albumu'].">".$item['nazwa_albumu']."</a></br>";
				}
				else
				{
					$q5=$link->query("select * from artysta where id_artysty='{$row4['id_artysty']}'");
					$row5=$q5->fetch_assoc();
					if($row5['pseudonim_artysty']=="")
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row5['id_artysty'].">".$row5['imie_artysty']." ".$row5['nazwisko_artysty']."</a> - <a href=album_wyswietl.php?id_a=".$item['id_albumu'].">".$item['nazwa_albumu']."</a></br>";
					}
					else
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row5['id_artysty'].">".$row5['imie_artysty']." ".'"'.$row5['pseudonim_artysty'].'"'." ".$row5['nazwisko_artysty']."</a> - <a href=album_wyswietl.php?id_a=".$item['id_albumu'].">".$item['nazwa_albumu']."</a></br>";
					}
				}
			}
            echo '</div>';
		}
		$link->close();
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>