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
			$q1=$link->query("select * from album where id_albumu='$id_a'");
			$row1=$q1->fetch_assoc();
			if(count($row1)==0)
			{
				header("location: albumy.php");exit;
			}
			$q2=$link->query("select piosenka.* from piosenka,album_to_piosenka where piosenka.id_piosenki=album_to_piosenka.id_piosenki and album_to_piosenka.id_albumu='$id_a'");
			$q3=$link->query("select gatunek.* from gatunek,album_to_gatunek where gatunek.id_gatunku=album_to_gatunek.id_gatunku and album_to_gatunek.id_albumu='$id_a'");
			$q4=$link->query("select wydanie.* from wydanie,album_to_wydanie where wydanie.id_wydania=album_to_wydanie.id_wydania and album_to_wydanie.id_albumu='$id_a'");
			$q5=$link->query("select artysta.* from artysta,album_to_artysta where artysta.id_artysty=album_to_artysta.id_artysty and album_to_artysta.id_albumu='$id_a'");
			$row5 = $q5->fetch_assoc();
			$q6=$link->query("select zespol.* from zespol,album_to_zespol where zespol.id_zespolu=album_to_zespol.id_zespolu and album_to_zespol.id_albumu='$id_a'");
			$row6=$q6->fetch_assoc();
			$q7=$link->query("select typ_albumu.* from typ_albumu where id_typu_albumu='{$row1['id_typu_albumu']}'");
			$row7=$q7->fetch_assoc();
			
			echo '<h2 class="heading-centre">'.$row1["nazwa_albumu"].'</h2>';
			
			if($basic_q2['id_typu_uzytkownika']==2)
			{
				echo '<div class="search-form"><div class="search-form-child"><b>Opcje moderatora: </b><a href="album_usun.php?id_a='.$row1['id_albumu'].'"> Usuń album</a></div></div></br>';
			}
			
			if(count($row5)>0)
			{
				if($row5['pseudonim_artysty']=="")
				{
					echo '<div class="container"><b>Autor: </b><a href="artysta_wyswietl.php?id_a='.$row5['id_artysty'].'">'.$row5['imie_artysty'].' '.$row5['nazwisko_artysty'].'<a><br>';
				}
				else
				{
					echo '<div class="container"><b>Autor: </b><a href="artysta_wyswietl.php?id_a='.$row5['id_artysty'].'">'.$row5['imie_artysty'].' "'.$row5['pseudonim_artysty'].'" '.$row5['nazwisko_artysty'].'</a><br>';
				}
			}
			else
			{
				if(count($row6)>0)
				{
					echo '<div class="container"><b>Autor: </b><a href="zespol_wyswietl.php?id_z='.$row6['id_zespolu'].'">'.$row6["nazwa_zespolu"]."</a><br>";
				}
				else
				{
					throw new Exception('COS POSZLO NIE TAK, NIE MA TAKIEGO ARTYSTY, ANI ZESPOLU!');
				}
			}
			
			echo "<b>Tytul: </b>".$row1["nazwa_albumu"]."<br>";
			echo "<b>Data wydania: </b>".$row1["data_wydania"]."<br>";
			echo "<b>Czas trwania: </b>".$row1["czas_trwania_albumu"]."<br>";
			
			if(count($row7)>0)
			{
				echo "<b>Typ: </b>".$row7["nazwa_typu_albumu"]."<br>";
			}
			else
			{
				throw new Exception('COS POSZLO NIE TAK PRZY POBIERANIU INFORMACJI O TYPIE ALBUMU!');
			}

			echo "<b>Gatunek: </b>";
			while($row3 = $q3->fetch_assoc())
			{
				echo $row3["nazwa_gatunku"]."; ";
			}
			echo "<br>";

			echo "<b>Wydania: </b>";
			while($row4 = $q4->fetch_assoc())
			{
				echo $row4["nazwa_wydania"]."; ";
			}
			echo "<br>";
			
			echo "<b>Tracklist: </b><br>";
			$i=1;
			while($row2 = $q2->fetch_assoc())
			{
				echo "<b>".$i.".</b> ".$row2["nazwa_piosenki"]."   ".$row2["czas_trwania_piosenki"]."<br>";
				$i++;
			}
			
			$ocena_id=0;
			
			$q8=$link->query("select * from album_to_uzytkownik_to_ocena where id_albumu='$id_a' and id_uzytkownika='{$basic_q1['ID_users']}'");
			
			$row8=$q8->fetch_assoc();
			
			if(count($row8)>0)
			{
				$ocena_id=$row8["id_oceny"];
			}
			
			$recenzja="";
			
			$q9=$link->query("select * from recenzja where id_albumu='$id_a' and id_uzytkownika='{$basic_q1['ID_users']}'");
			
			$row9=$q9->fetch_assoc();
			
			if(count($row9)>0)
			{
				$recenzja=$row9["recenzja"];
				$recenzja_exists=true;
			}
		
			if(isset($_POST['ocena']))
			{
                if($_POST['ocena']<1||$_POST['ocena']>10||!is_numeric($_POST['ocena']))
                {
                    $_SESSION['err_ocena']="Ocena może być jedynie liczbą z przedziału 1-10!";
                }
                else
                {
                    $ocena=$_POST['ocena'];
                    if(count($row8)>0)
                    {
                        $rezultat1=$link->query("UPDATE album_to_uzytkownik_to_ocena set id_oceny='$ocena' where id_albumu='$id_a' and id_uzytkownika='{$basic_q1['ID_users']}'");
                            if (!$rezultat1) throw new Exception($link->error);
                            $ocena_id=$ocena;
                    }
                    else
                    {
                        $rezultat1=$link->query("INSERT INTO album_to_uzytkownik_to_ocena VALUES ('$id_a','{$basic_q1['ID_users']}','$ocena')");
                            if (!$rezultat1) throw new Exception($link->error);
                            $ocena_id=$ocena;
                    }
                }
			}
			
			$q8=$link->query("select * from album_to_uzytkownik_to_ocena where id_albumu='$id_a' and id_uzytkownika='{$basic_q1['ID_users']}'");
			
			$row8=$q8->fetch_assoc();
			
			if(count($row8)>0)
			{
				$ocena_id=$row8["id_oceny"];
			}
			
			$q12=$link->query("select * from album_to_uzytkownik_to_ocena where id_albumu='$id_a'");
			$i_o=0;
			$suma_o=0;
			while($row12 = $q12->fetch_assoc())
			{
				$i_o++;
				$suma_o+=$row12['id_oceny'];
			}
			if($i_o!=0)
			{
				echo "<b>Srednia ocena: </b>".round($suma_o/$i_o,2)."<br></div>";
			}
			else
			{
				echo "<b>Srednia ocena: </b>Ten album nie ma jeszcze średniej oceny<br></div>";
			}
			
			if(isset($recenzja_exists)&&isset($_POST['recenzja_value']))
			{
				$recenzja=mysqli_real_escape_string($link,$_POST['recenzja_value']);
				$recenzja=htmlspecialchars($recenzja);
                 $rezultat2=$link->query("UPDATE recenzja set recenzja='$recenzja' where id_albumu='$id_a' and id_uzytkownika='{$basic_q1['ID_users']}'");
                      if (!$rezultat2) throw new Exception($link->error);
			}
			if(!(isset($recenzja_exists))&&isset($_POST['recenzja_value']))
			{
				$recenzja=mysqli_real_escape_string($link,$_POST['recenzja_value']);
				$recenzja=htmlspecialchars($recenzja);
                 $rezultat3=$link->query("INSERT INTO recenzja VALUES ('{$basic_q1['ID_users']}','$id_a','$recenzja')");
                       if (!$rezultat3) throw new Exception($link->error);
            }
			
			$q10=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}'");
			//$q10=$link->query("select lista.* from lista,album_to_lista where lista.id_listy=album_to_lista.id_listy AND lista.id_uzytkownika='{$basic_q1['ID_users']}' AND album_to_lista.id_albumu NOT LIKE '$id_a'");
            
            
			$listArray=[];
			$listWithKey=array();
			$i=0;
			while($row = $q10->fetch_assoc()) 
			{
				$listArray[$i]=$row['id_listy'];
				$listWithKey[$row['id_listy']]=$row['nazwa_listy'];
				$i++;	
			}
			
			if(isset($_POST['listy']))
			{
				$listy=$_POST['listy'];
				
				foreach($listy as $item1)
				{
					$q11=$link->query("select * from album_to_lista where id_listy='$item1' and id_albumu='$id_a'");
					
					$row11=$q11->fetch_assoc();
					
					if(count($row11)>0)
					{
						$_SESSION['err_lista']="Ten album jest już na liście ".$listWithKey[$item1]."</br>";
					}
					else
					{
						$rezultat4=$link->query("INSERT INTO album_to_lista VALUES ('$id_a','$item1')");
							if (!$rezultat4) throw new Exception($link->error);
                        $_SESSION['lista_ok']="Dodano album do listy ".$listWithKey[$item1]."</br>";
                    }
				}
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
	?>
	<h2></h2>
    <div class="rating-form">
	<form method="post">
		<div id="oceny">
			<input type="radio" name="ocena" value="1" id="1">1
			<input type="radio" name="ocena" value="2" id="2">2
			<input type="radio" name="ocena" value="3" id="3">3
			<input type="radio" name="ocena" value="4" id="4">4
			<input type="radio" name="ocena" value="5" id="5">5
			<input type="radio" name="ocena" value="6" id="6">6
			<input type="radio" name="ocena" value="7" id="7">7
			<input type="radio" name="ocena" value="8" id="8">8
			<input type="radio" name="ocena" value="9" id="9">9
			<input type="radio" name="ocena" value="10" id="10">10
		</div>
		<input type="submit" value="Oceń album">
        <?php
            if(isset($_SESSION['err_ocena']))
            {
                echo '<div class="error">'.$_SESSION['err_ocena'].'</div>';
                unset($_SESSION['err_ocena']);
            }
        ?>
	</form>
	<br>
        <span><b>Twoja rezcenja</b></span>
	<form method="post" id="recenzja">
		<textarea rows="10" cols="100" name="recenzja_value" id="recenzja_text">
		</textarea><br>
		<input type="submit" value="Zapisz recenzję">
	</form>
        <br>
	<button type="button" id="add_to_list">Dodaj album do listy</button>
    <?php
        if(isset($_SESSION['err_lista']))
        {
            echo '<div class="error">'.$_SESSION['err_lista'].'</div>';
            unset($_SESSION['err_lista']);
        }
        if(isset($_SESSION['lista_ok']))
        {
            echo '<div class="ok">'.$_SESSION['lista_ok'].'</div>';
            unset($_SESSION['lista_ok']);
        }
    ?>
	<form method="post">
		<div id="listID">
		</div>
	</form>
     </div>
	<?php
        require_once("stopka.php");
    ?>
    
	<?php
	
		$q13=$link->query("select * from recenzja where id_albumu='$id_a' and id_uzytkownika NOT LIKE'{$basic_q1['ID_users']}'");
			
		//$row12=$q12->fetch_assoc();
	
		//WYSWIETLANIE INNYCH RECENZJI
		
        echo '<h2 class="heading-centre">Recenzje innych użytkowników</h2>';
        
		while($row13 = $q13->fetch_assoc())
		{
			$q14=$link->query("select login from uzytkownik where id_uzytkownika='{$row13['id_uzytkownika']}'");
			$row14=$q14->fetch_assoc();
			echo '<div class="container"><b>Rezencja uzytkownika '.$row14['login']."</b></br>";
			echo $row13['recenzja']."<br></div>";
		}
		
		$link->close();
	?>
	
	<script>
		$(document).ready(function(){
			$("#add_to_list").click(function(){
				var list=<?php echo json_encode($listWithKey); ?>;
				var indexes = Object.keys(list);
				for(var i=0;i<indexes.length;i++)
				{
					var index = indexes[i];
					var input='<input type="checkbox" name="listy[]" value="'+index+'" id="'+index+'"/>'+list[index]+'</br>';
					$("#listID").append(input);
				}
				$("#listID").append('<input type="submit" value="Potwierdź dodanie do listy"/>');
			});
		});
	</script>
    
	<script>
		var rec="<?php echo $recenzja ?>";
		document.getElementById('recenzja_text').innerHTML=rec;
	</script>
    
	<script>
		var o = <?php echo $ocena_id ?>;
		if(o>0)
		{
			let radioButton=document.getElementById(o);
			radioButton.checked=true;
		}
	</script>

</body>