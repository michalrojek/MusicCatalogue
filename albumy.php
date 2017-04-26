<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src=https://code.jquery.com/jquery-3.1.1.js type="text/javascript"></script>
</head>
<body>
    <?php
        //EWENTUALNIE ZROBIC:
        //TRY CATCH ABY W RAZIE PRZYPADKU GDZIE DANE UZYSKANE Z $_GET NIE SA NUMERAMI, COFNAC UZYTKOWNIKA DO ZWYKLEJ STRONY ALBUMOW
        require_once('menu.php');
    ?>
	<h2 class="heading-centre">Albumy</h2>
    <div class="search-form">
        <div class="search-from-child">
        <form method="post">
            <input type="text" name="album_wyszukaj" placeholder="Wyszukaj album">

            <?php

                $q3=$link->query("select * from gatunek");
                echo '<select name="gatunek_wyszukaj"><option>Gatunek</option>';
                while($row3=$q3->fetch_assoc())
                {
                    echo '<option value="'.$row3['id_gatunku'].'">'.$row3['nazwa_gatunku'].'</option>';
                }
                echo "</select>";

                $q3=$link->query("select * from typ_albumu");

                echo '<select name="typ_wyszukaj"><option>Typ</option>';
                while($row3=$q3->fetch_assoc())
                {
                    echo '<option value="'.$row3['id_typu_albumu'].'">'.$row3['nazwa_typu_albumu'].'</option>';
                }
                echo "</select>";

                $q3=$link->query("select * from wydanie");
                echo '<select name="wydanie_wyszukaj"><option>Wydanie</option>';
                while($row3=$q3->fetch_assoc())
                {
                    echo '<option value="'.$row3['id_wydania'].'">'.$row3['nazwa_wydania'].'</option>';
                }
                echo "</select>";

            ?>

            <input type="submit" value="Wyszukaj album">
        </form>
            </div>
    </div>
	<?php
		if(isset($_GET['page']))
		{
			$page=$_GET['page'];
		}
		else
		{
			$page=0;
		}
		if(isset($_POST['album_wyszukaj'])||isset($_POST['gatunek_wyszukaj'])||isset($_POST['typ_wyszukaj'])||isset($_POST['wydanie_wyszukaj'])||
		isset($_GET['album_wyszukaj'])||isset($_GET['gatunek_wyszukaj'])||isset($_GET['typ_wyszukaj'])||isset($_GET['wydanie_wyszukaj']))
		{
			
			//GET BEZ KONTROLI
			if(isset($_GET['album_wyszukaj'])&&!(strlen($_GET["album_wyszukaj"]) > 0 && strlen(trim($_GET["album_wyszukaj"])) == 0))
			{
				$nazwa_a=mysqli_real_escape_string($link,$_GET['album_wyszukaj']);
				$nazwa_a=htmlspecialchars($nazwa_a);
			}
			
			if(isset($_GET['wydanie_wyszukaj'])&&(is_numeric($_GET['wydanie_wyszukaj'])||$_GET['wydanie_wyszukaj']=='%%'))
			{
				$wyd_id=$_GET['wydanie_wyszukaj'];
			}
			
			if(isset($_GET['typ_wyszukaj'])&&(is_numeric($_GET['typ_wyszukaj'])||$_GET['typ_wyszukaj']=='%%'))
			{
				$typ_id=$_GET['typ_wyszukaj'];
			}
			
			if(isset($_GET['gatunek_wyszukaj'])&&(is_numeric($_GET['gatunek_wyszukaj'])||$_GET['gatunek_wyszukaj']=='%%'))
			{
				$gat_id=$_GET['gatunek_wyszukaj'];
			}
			
			//POST I KONTROLA PRZESLANYCH DANYCH
			if(isset($_POST['album_wyszukaj'])&&!(strlen($_POST["album_wyszukaj"]) > 0 && strlen(trim($_POST["album_wyszukaj"])) == 0)&&$_POST["album_wyszukaj"]!="")
			{
				$nazwa_a=mysqli_real_escape_string($link,$_POST['album_wyszukaj']);
				$nazwa_a=htmlspecialchars($nazwa_a);
			}
			else
			{
				$nazwa_a="";
			}
			
			if(isset($_POST['wydanie_wyszukaj'])&&$_POST['wydanie_wyszukaj']!="Wydanie")
			{
				$wyd_id=$_POST['wydanie_wyszukaj'];
			}
			else
			{
				$wyd_id="%%";
			}
			
			if(isset($_POST['typ_wyszukaj'])&&$_POST['typ_wyszukaj']!="Typ")
			{
				$typ_id=$_POST['typ_wyszukaj'];
			}
			else
			{
				$typ_id='%%';
			}
			
			if(isset($_POST['gatunek_wyszukaj'])&&$_POST['gatunek_wyszukaj']!="Gatunek")
			{
				$gat_id=$_POST['gatunek_wyszukaj'];
			}
			else
			{
				$gat_id="%%";
			}
			
			$limit=$page*10;
			
			$q1=$link->query("select DISTINCT album.* from album,album_to_gatunek,album_to_wydanie where album.id_albumu=album_to_gatunek.id_albumu and album.id_albumu=album_to_wydanie.id_albumu 
			and album.nazwa_albumu COLLATE UTF8_GENERAL_CI LIKE '%{$nazwa_a}%' and album_to_gatunek.id_gatunku LIKE '$gat_id' and album.id_typu_albumu LIKE '$typ_id' and album_to_wydanie.id_wydania LIKE '$wyd_id' LIMIT $limit,10");

			while($row = $q1->fetch_assoc()) 
			{
				$q2=$link->query("select artysta.* from artysta,album_to_artysta where artysta.id_artysty=album_to_artysta.id_artysty and album_to_artysta.id_albumu='{$row['id_albumu']}'");
				$row2=$q2->fetch_assoc();
				if(count($row2))
				{
					if($row2['pseudonim_artysty']=="")
					{
						echo '<div class="container"><b>Wykonawca: </b><a href=artysta_wyswietl.php?id_a='.$row2['id_artysty'].">".$row2['imie_artysty'].' '.$row2['nazwisko_artysty']."</a><br>
                        <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}
					else
					{
						echo '<div class="container"><b>Wykonawca: </b><a href=artysta_wyswietl.php?id_a='.$row2['id_artysty'].">".$row2['imie_artysty'].' "'.$row2['pseudonim_artysty'].'" '.$row2['nazwisko_artysty']."</a><br>
                        <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}
				}
				else
				{
					$q2=$link->query("select zespol.* from zespol,album_to_zespol where zespol.id_zespolu=album_to_zespol.id_zespolu and album_to_zespol.id_albumu='{$row['id_albumu']}'");
					$row2=$q2->fetch_assoc();
					if(count($row2))
					{
						echo '<div class="container"><b>Wykonawca: </b><a href=zespol_wyswietl.php?id_z='.$row2['id_zespolu'].">".$row2['nazwa_zespolu']."</a><br>
                        <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}
					else
					{
						echo "cos poszlo nie tak</br>";
					}
				}
			}
			
			echo '</br></br>';
			$q2=$link->query("select DISTINCT album.* from album,album_to_gatunek,album_to_wydanie where album.id_albumu=album_to_gatunek.id_albumu and album.id_albumu=album_to_wydanie.id_albumu 
			and album.nazwa_albumu COLLATE UTF8_GENERAL_CI LIKE '%{$nazwa_a}%' and album_to_gatunek.id_gatunku LIKE '$gat_id' and album.id_typu_albumu LIKE '$typ_id' and album_to_wydanie.id_wydania LIKE '$wyd_id'");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="albumy.php?page='.$i.'&album_wyszukaj='.$nazwa_a.'&wydanie_wyszukaj='.$wyd_id.'&typ_wyszukaj='.$typ_id.'&gatunek_wyszukaj='.$gat_id.'">'.($i+1).'</a> ';
                }
                else
                {
                    echo ' '.($i+1).' ';
                }
			}
            echo '</div>';
		}
		else
		{
			$limit=$page*10;
			
			$q1=$link->query("select * from album LIMIT $limit,10");
			
			while($row = $q1->fetch_assoc()) 
			{
				$q2=$link->query("select artysta.* from artysta,album_to_artysta where artysta.id_artysty=album_to_artysta.id_artysty and album_to_artysta.id_albumu='{$row['id_albumu']}'");
				$row2=$q2->fetch_assoc();
				if(count($row2))
				{
					if($row2['pseudonim_artysty']=="")
					{
						echo '<div class="container"><b>Wykonawca: </b><a href="artysta_wyswietl.php?id_a='.$row2['id_artysty'].'">'.$row2['imie_artysty'].' '.$row2['nazwisko_artysty']."</a><br>
                            <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}
					else
					{
						echo '<div class="container"><b>Wykonawca: </b><a href="artysta_wyswietl.php?id_a='.$row2['id_artysty'].'">'.$row2['imie_artysty'].' "'.$row2['pseudonim_artysty'].'" '.$row2['nazwisko_artysty']."</a><br>
                        <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}	
				}
				else
				{
					$q2=$link->query("select zespol.* from zespol,album_to_zespol where zespol.id_zespolu=album_to_zespol.id_zespolu and album_to_zespol.id_albumu='{$row['id_albumu']}'");
					$row2=$q2->fetch_assoc();
					if(count($row2))
					{
						echo '<div class="container"><b>Wykonawca: </b><a href=zespol_wyswietl.php?id_z='.$row2['id_zespolu'].">".$row2['nazwa_zespolu']."</a><br>
                            <b>Tytuł: </b><a href=album_wyswietl.php?id_a=".$row["id_albumu"].">".$row["nazwa_albumu"]."</a></br></div>";
					}
					else
					{
						echo "cos poszlo nie tak</br>";
					}
				}
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from album");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="albumy.php?page='.$i.'">'.($i+1).'</a> ';
                }
                else
                {
                    echo ' '.($i+1).' ';
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