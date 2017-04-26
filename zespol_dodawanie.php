<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src=https://code.jquery.com/jquery-3.1.1.js type="text/javascript"></script>
</head>
<body>
    <?php
        require_once('zalogowano.php');
    ?>

	<?php
		$wszystko_ok=true;
			
		if(!(strlen($_POST['z_nazwa']) > 0 && strlen(trim($_POST['z_nazwa'])) == 0)&&$_POST['z_nazwa']!="")
		{
			$z_nazwa=mysqli_real_escape_string($link,$_POST["z_nazwa"]);
			$z_nazwa=htmlspecialchars($z_nazwa);
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_nazwa']="Podaj nazwę zespołu!";
		}
		
		if($_POST['z_data']=="")
		{
			$wszystko_ok=false;
			$_SESSION['err_data']="Podaj datę powstania zespołu!";
		}
		else
		{
			$z_data=$_POST['z_data'];
		}

		$i=0;
		
		foreach($_POST['z_czlonkowie'] as $item)
		{
			if(!(strlen($item) > 0 && strlen(trim($item)) == 0)&&$item!="")
			{
				$z_czlonkowie[$i]=mysqli_real_escape_string($link,$item);
				$z_czlonkowie[$i]=htmlspecialchars($z_czlonkowie[$i]);
				$i++;	
			}
			else
			{
				$wszystko_ok=false;
				$_SESSION['err_czlonkowie'][$i]="Podaj imię i nazwisko członka zespołu! ";
				$z_czlonkowie[$i]=mysqli_real_escape_string($link,$item);
				$z_czlonkowie[$i]=htmlspecialchars($z_czlonkowie[$i]);
				$i++;
			}
		}
		
		$i=0;
		foreach($z_czlonkowie as $item)
		{
			if(!(strlen($item) > 0 && strlen(trim($item)) == 0)&&$item!="")
			{
				$q2=$link->query("select * from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$item'
				or concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$item'");
				$row2=$q2->fetch_assoc();
				
				if(count($row2)==0)
				{
					$wszystko_ok=false;
					$_SESSION['err_czlonkowie'][$i]="Nie ma takiego artysty w bazie danych! ";
				}
			}
			$i++;
		}
		
		$i=0;
		foreach($_POST['z_czlonkowie_data'] as $item)
		{
			if($item=="")
			{
				$wszystko_ok=false;
				$_SESSION['err_czlonkowie_data'][$i]="Podaj datę rozpoczęcia współpracy!";
				$i++;
			}
            else if(isset($z_data)&&$item<$z_data)
            {
                 $wszystko_ok=false;
				 $_SESSION['err_czlonkowie_data'][$i]="Data rozpoczęcia współpracy nie moze być mniejsza niż data założenia zespołu!";
                 $i++;
            }
            else if(!isset($_SESSION['err_czlonkowie_data'][$i]))
            {
                $q2=$link->query("select YEAR(data_ur_artysty) as rok from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonkowie[$i]'
				or concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonkowie[$i]'");
				$row2=$q2->fetch_assoc();
                if($row2['rok']>$item)
                {
                    $wszystko_ok=false;
				    $_SESSION['err_czlonkowie_data'][$i]="Data rozpoczęcia współpracy nie moze być mniejsza niż data urodzenia artysty!";
                    $i++;  
                }
            }
			else
			{
				$z_czlonkowie_data[$i]=$item;
				$i++;
			}
		}
		
		$q2=$link->query("select * from zespol where nazwa_zespolu LIKE '$z_nazwa'");
		$row2=$q2->fetch_assoc();
		
		if(count($row2)>0)
		{
			$wszystko_ok=false;
			$_SESSION['err_nazwa']="W bazie już istnieje zespół o takiej nazwie!";
		}
		
		if($wszystko_ok)
		{
			try{				
				$rezultat1=$link->query("INSERT INTO zespol VALUES (NULL, '$z_nazwa', '$z_data')");
				if (!$rezultat1) throw new Exception($link->error);
				
				$q1=mysqli_fetch_assoc(mysqli_query($link,"select id_zespolu from zespol where nazwa_zespolu='$z_nazwa' and rok_rozp_dzial='$z_data'"));
				if(!$q1) throw new Exception($link->error);
				$z_id=$q1['id_zespolu'];
				
				$i=0;
				foreach($z_czlonkowie as $item)
				{
						$rezultat2 =mysqli_fetch_assoc(mysqli_query($link,"select id_artysty from artysta where concat(imie_artysty,' ',nazwisko_artysty) LIKE '$item' 
						OR concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) LIKE '$item' OR pseudonim_artysty='$item'"));
						if(!$rezultat2)
						{
							throw new Exception($link->error);
						}
						else
						{
							$a_id=$rezultat2['id_artysty'];
							if ($link->query("INSERT INTO artysta_to_zespol VALUES ('$z_id', '$a_id','{$_POST['z_czlonkowie_data'][$i]}',0)"))
							{
								$i++;
							}
							else
							{
								throw new Exception($link->error);
							}
						}			
				}
				
				$_SESSION['udanedodanie']=true;
				header('Location:zespol_dodaj.php');
				
				$link->close();
			}catch(Exception $e)
			{
				echo '<br />Informacja developerska: '.$e;
			}
		}
		else
		{
			$_SESSION['z_nazwa']=$_POST['z_nazwa'];
			$_SESSION['z_data']=$_POST['z_data'];
			$_SESSION['z_czlonkowie']=$_POST['z_czlonkowie'];
			$_SESSION['z_czlonkowie_data']=$_POST['z_czlonkowie_data'];
			header("location:zespol_dodaj.php");exit;
		}
	?>
</body>