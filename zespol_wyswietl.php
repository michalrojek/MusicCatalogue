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

        if (isset($_SESSION['udanaedycja']))
        {
            echo '<h3 class="heading-centre"><span style="color:green;">Rok zakończenia współpracy został dodany!</span></h3></br>';
            unset($_SESSION['udanaedycja']);
        }
    ?>
	<?php
		try
		{
			$id_z=$_GET['id_z'];
			$q1=$link->query("select * from zespol where id_zespolu='$id_z'");
			$row1=$q1->fetch_assoc();
			if(count($row1)==0)
			{
				header("location: zespoly.php");exit;
			}
			$q2=$link->query("select artysta.*,artysta_to_zespol.* from artysta,artysta_to_zespol where artysta.id_artysty=artysta_to_zespol.id_artysty and artysta_to_zespol.id_zespolu='$id_z'");
			$q3=$link->query("select album.* from album,album_to_zespol where album.id_albumu=album_to_zespol.id_albumu and album_to_zespol.id_zespolu='$id_z'");

			echo '<h2 class="heading-centre">'.$row1['nazwa_zespolu'].'</h2>';
			
			if($basic_q2['id_typu_uzytkownika']==2)
			{
				echo '<div class="search-form"><div class="search-form-child"><b>Opcje moderatora: </b><a href="zespol_usun.php?id_z='.$row1['id_zespolu'].'"> Usun zespół</a> ';
				echo '<a href="zespol_dodaj_cz.php?id_z='.$row1['id_zespolu'].'"> Dodaj nowego członka zespołu</a></br></div></div>';
			}
			
			echo '<div class="container"><b>Nazwa zespołu: </b>'.$row1['nazwa_zespolu']."</br>";
            echo "<b>Rok rozpoczęcia działalności: </b>".$row1['rok_rozp_dzial']."</br>";
			
			echo "<b>Członkowie: </b>";
			$i_z=0;
			while($row2 = $q2->fetch_assoc())
			{
				$i_z++;
				if($row2['rok_zakon_wspolpracy']!=0)
				{
					if($row2['pseudonim_artysty']=="")
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row2["id_artysty"].">".$row2["imie_artysty"].' '.$row2["nazwisko_artysty"]."</a>(".$row2['rok_rozp_wspolpracy']." - ".$row2['rok_zakon_wspolpracy']."); ";
					}
					else
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row2["id_artysty"].">".$row2["imie_artysty"].' "'.$row2['pseudonim_artysty'].'" '.$row2["nazwisko_artysty"]."</a>(".$row2['rok_rozp_wspolpracy']." - ".$row2['rok_zakon_wspolpracy']."); ";
					}
				}
				else
				{
					if($row2['pseudonim_artysty']=="")
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row2["id_artysty"].">".$row2["imie_artysty"].' '.$row2["nazwisko_artysty"]."</a>(".$row2["rok_rozp_wspolpracy"]."-teraz)";
						if($basic_q2['id_typu_uzytkownika']==2)
						{
							echo '<a href="zespol_cz_data.php?id_z='.$row1['id_zespolu'].'&id_a='.$row2['id_artysty'].'&rok='.$row2['rok_rozp_wspolpracy'].'"> (Dodaj rok zakończenia współpracy)</a>';
						}
						echo "; ";
					}
					else
					{
						echo "<a href=artysta_wyswietl.php?id_a=".$row2["id_artysty"].">".$row2["imie_artysty"].' "'.$row2['pseudonim_artysty'].'" '.$row2["nazwisko_artysty"]."</a>(".$row2["rok_rozp_wspolpracy"]."-teraz)";
						if($basic_q2['id_typu_uzytkownika']==2)
						{
							echo '<a href="zespol_cz_data.php?id_z='.$row1['id_zespolu'].'&id_a='.$row2['id_artysty'].'&rok='.$row2['rok_rozp_wspolpracy'].'"> (Dodaj rok zakończenia współpracy)</a>';
						}
						echo "; ";
					}
				}
			}
			if($i_z==0)
			{
				echo "Ten zespół nie ma/miał żadnego członka";
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
				echo '<span style="margin-left:1em">Ten zespół nie nagrał żadnej płyty</br></span></div>';
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