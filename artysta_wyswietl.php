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
		try
		{
			
			$id_a=$_GET['id_a'];
			$q1=$link->query("select * from artysta where id_artysty='$id_a'");
			$row1=$q1->fetch_assoc();
			if(count($row1)==0)
			{
				header("location: artysci.php");exit;
			}
			$q2=$link->query("select zespol.*,artysta_to_zespol.* from zespol,artysta_to_zespol where zespol.id_zespolu=artysta_to_zespol.id_zespolu and artysta_to_zespol.id_artysty='$id_a'");
			$q3=$link->query("select album.* from album,album_to_artysta where album.id_albumu=album_to_artysta.id_albumu and album_to_artysta.id_artysty='$id_a'");

			if($row1['pseudonim_artysty']!="")
			{
				echo '<h2 class="heading-centre">'.$row1["imie_artysty"].' "'.$row1["pseudonim_artysty"].'" '.$row1["nazwisko_artysty"].'</h2>';
			}
			else
			{
				echo '<h2 class="heading-centre">'.$row1["imie_artysty"].' '.$row1["nazwisko_artysty"].'</h2>';
			}
			
			if($basic_q2['id_typu_uzytkownika']==2)
			{
				echo '<div class="search-form"><div class="search-form-child"><b>Opcje moderatora: </b><a href="artysta_usun.php?id_a='.$row1['id_artysty'].'"> Usuń artystę</a> ';
				echo '<a href="artysta_edytuj.php?id_a='.$row1['id_artysty'].'"> Edytuj dane artysty</a></br></div></div>';
			}
			
			if($row1['pseudonim_artysty']!="")
			{
				echo '<div class="container"><b>Artysta: </b>'.$row1["imie_artysty"].' "'.$row1["pseudonim_artysty"].'" '.$row1["nazwisko_artysty"].'</br>';
			}
			else
			{
				echo '<div class="container"><b>Artysta: </b>'.$row1["imie_artysty"].' '.$row1["nazwisko_artysty"].'</br>';
			}
			
            echo '<b>Kraj pochodzenia: </b>'.$row1["kraj_pochodzenia_artysty"].'</br>';
            echo '<b>Data urodzenia: </b>'.$row1["data_ur_artysty"].'</br>';
            
			echo "<b>Członek: </b>";
			$i_z=0;
			while($row2 = $q2->fetch_assoc())
			{
				$i_z++;
				if($row2['rok_zakon_wspolpracy']!=0)
				{
					echo "<a href=zespol_wyswietl.php?id_z=".$row2["id_zespolu"].">".$row2["nazwa_zespolu"]."</a>(".$row2['rok_rozp_wspolpracy']." - ".$row2['rok_zakon_wspolpracy']."); ";
				}
				else
				{
					echo "<a href=zespol_wyswietl.php?id_z=".$row2["id_zespolu"].">".$row2["nazwa_zespolu"]."</a>; ";
				}
			}
			if($i_z==0)
			{
				echo "Ten artysta nie jest/był członkiem żadnego zespołu";
			}
			echo "</br>";
			
			echo "<b>Dyskografia: </b></br>";
			$i_a=0;
			while($row3 = $q3->fetch_assoc())
			{
				$i_a++;
				{
					echo "<a href=album_wyswietl.php?id_a=".$row3["id_albumu"].'><span style="margin-left:1em">'.$row3["nazwa_albumu"]."</span></a></br>";
				}
			}
			if($i_a==0)
			{
				echo '<span style="margin-left:1em">Ten artysta nie nagrał żadnej solowej płyty</br></span></div>';
			}
            else
            {
                echo '</div>';
            }
			echo "</br>";

		}			
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>