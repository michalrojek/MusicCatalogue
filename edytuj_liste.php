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
			$q1=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}' and id_listy='$id_l'");
			$lista_dane=$q1->fetch_assoc();
            if(count($lista_dane)==0)
            {
                header("location:moje_listy.php");
            }
			
			echo '<h2 class="heading-centre">Edytowanie listy: '.$lista_dane['nazwa_listy'].'</h2>';
			
			$q2=$link->query("select * from album_to_lista where id_listy='$id_l'");
			
			if(isset($_POST['nazwa_listy']))
			{
				$nazwa_listy=mysqli_real_escape_string($link,$_POST['nazwa_listy']);
				$nazwa_listy=htmlspecialchars($nazwa_listy);
				
				$q4=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}'");
				
				while($row = $q4->fetch_assoc())
				{
					if($nazwa_listy==$row['nazwa_listy'])
					{
						$_SESSION['err_nazwa']= "Juz posiadasz listę o takiej nazwie. Spróbuj jeszcze raz.</br>";
						break;
					}
					else
					{
						$noList=true;
					}
				}
				
				if(isset($noList)&&$noList)
				{
                    try
                    {
                        $rezultat2=$link->query("update lista set nazwa_listy='$nazwa_listy' where id_listy='$id_l'");
                        if(!$rezultat2) throw new Exception($link->error);
                        $q1=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}' and id_listy='$id_l'");
                        $lista_dane=$q1->fetch_assoc();
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
				}
			}
			
			if(isset($_POST['opis_value']))
			{
				$opis_listy=mysqli_real_escape_string($link,$_POST['opis_value']);
				$opis_listy=htmlspecialchars($opis_listy);
				
                try
                {
                    $rezultat2=$link->query("update lista set opis_listy='$opis_listy' where id_listy='$id_l'");
                    if(!$rezultat2) throw new Exception($link->error);
                    $q1=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}' and id_listy='$id_l'");
                    $lista_dane=$q1->fetch_assoc();
                }catch(Exception $e)
                {
                    echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                    echo '<br />Informacja developerska: '.$e;
                }
			}
	?>
	<div class="search-form"><div class="search-form-child">
	<form method="post">
		<b>Nazwa listy: </b><input type="text" name="nazwa_listy" value="<?php echo $lista_dane['nazwa_listy']; ?>">
		<input type="submit" value="Edytuj nazwę"/>
        <?php
            if(isset($_SESSION['err_nazwa']))
            {
                echo '<br><div class="error">'.$_SESSION['err_nazwa'].'</div>';
				unset($_SESSION['err_nazwa']);
            }
            ?>
	</form>
        <br><b>Opis listy:</b><br>
	<form method="post">
		<textarea rows="10" cols="100" name="opis_value" id="opis_text"><?php echo $lista_dane['opis_listy']; ?></textarea><br>
		<input type="submit" value="Edytuj opis"/>
	</form>
        </div></div>
	<?php
			echo "<br>";
			$albumy=[];
			$i=0;
			while($row = $q2->fetch_assoc()) 
			{
				$q3=$link->query("select * from album where id_albumu='{$row['id_albumu']}'");
				$albumy[$i]=$q3->fetch_assoc();
				$i++;
			}
            echo '<div class="container">';
			foreach($albumy as $item)
			{
				$q1=mysqli_fetch_assoc(mysqli_query($link,"select artysta.* from artysta,album_to_artysta where artysta.id_artysty=album_to_artysta.id_artysty AND 
				album_to_artysta.id_albumu='{$item['id_albumu']}'"));
				
				if(!empty($q1))
				{
					if($q1['pseudonim_artysty']=="")
					{
						echo '<a href="artysta_wyswietl.php?id_a='.$q1['id_artysty'].'">'.$q1['imie_artysty'].' '.$q1['nazwisko_artysty'].'</a> - <a href="album_wyswietl.php?id_a='
						.$item['id_albumu'].'">'.$item['nazwa_albumu']."</a> <a href=lista_album_usun.php?id_l=".$id_l."&id_a=".$item["id_albumu"].">Usun album z listy</a></br>";
					}
					else
					{
						echo '<a href="artysta_wyswietl.php?id_a='.$q1['id_artysty'].'">'.$q1['imie_artysty'].' '.$q1['pseudonim_artysty'].' '.$q1['nazwisko_artysty'].'</a> - <a href="album_wyswietl.php?id_a='
						.$item['id_albumu'].'">'.$item['nazwa_albumu']."</a> <a href=lista_album_usun.php?id_l=".$id_l."&id_a=".$item["id_albumu"].">Usun album z listy</a></br>";	
					}
				}
				else
				{
					$q1=mysqli_fetch_assoc(mysqli_query($link,"select zespol.* from zespol,album_to_zespol where zespol.id_zespolu=album_to_zespol.id_zespolu AND 
					album_to_zespol.id_albumu='{$item['id_albumu']}'"));
					
					echo '<a href="zespol_wyswietl.php?id_z='.$q1['id_zespolu'].'">'.$q1['nazwa_zespolu'].'</a> - <a href="album_wyswietl.php?id_a='.$item['id_albumu'].'">'
					.$item['nazwa_albumu']."</a> <a href=lista_album_usun.php?id_l=".$id_l."&id_a=".$item["id_albumu"].">Usun album z listy</a></br>";
				}
			}
            echo '</div>';
		}
        else
        {
            header("location:moje_listy.php");
        }
		$link->close();
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>