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

        if($basic_q2['id_typu_uzytkownika']==1)
        {
            header('location:strona_glowna.php'); exit;
        }

    ?>
	<?php
		if(isset($_GET['id_z'])&&is_numeric($_GET['id_z'])&&isset($_GET['id_a'])&&is_numeric($_GET['id_a'])&&isset($_GET['rok'])&&is_numeric($_GET['rok']))
		{
			$id_z=$_GET['id_z'];
			$id_a=$_GET['id_a'];
            $rok_rozp_wspol=$_GET['rok'];
			
			$wszystko_ok=true;
			
			$q1=mysqli_fetch_assoc(mysqli_query($link,"select * from artysta_to_zespol where id_zespolu='$id_z' AND id_artysty='$id_a' AND rok_rozp_wspolpracy='$rok_rozp_wspol'"));
			if(empty($q1)||$q1['rok_zakon_wspolpracy']!=0)
			{
				header('location:zespoly.php');exit;
			}
			if(isset($_POST['z_czlonek_data']))
			{
				
				if($_POST['z_czlonek_data']=="")
				{
					$wszystko_ok=false;
					$_SESSION['err_czlonek_data']="Podaj datę zakończenia współpracy!";
				}
				else
				{
					$z_czlonek_data=$_POST['z_czlonek_data'];
					if($z_czlonek_data<$q1['rok_rozp_wspolpracy'])
					{
						$wszystko_ok=false;
						$_SESSION['err_czlonek_data']="Data zakończenia współpracy nie może być mniejsza niż data ropoczęcia wspolpracy!";
					}
				}
				
				if($wszystko_ok)
				{
					
					try
					{
						$rezultat2 =mysqli_query($link,"UPDATE artysta_to_zespol SET rok_zakon_wspolpracy='$z_czlonek_data' WHERE id_zespolu='$id_z' AND id_artysty='$id_a'");
						if(!$rezultat2)
						{
							throw new Exception($link->error);
						}
						else
						{
							$_SESSION['udanaedycja']=true;
							header('Location:zespol_wyswietl.php?id_z='.$_GET['id_z']);exit;
						}			
					}
					catch(Exception $e)
					{
						echo '<br />Informacja developerska: '.$e;
					}
				}
				else
				{
					$_SESSION['z_czlonek_data']=$_POST['z_czlonek_data'];
					header('location:zespol_cz_data.php?id_z='.$_GET['id_z'].'&id_a='.$_GET['id_a'].'&rok='.$_GET['rok']);exit;
				}
			}
			
		}
		else
		{
			header('location:zespoly.php');exit;
		}
	?>
	<form method="post">
		<h3 class="heading-centre">Podaj rok zakończenia współpracy:</h3>
        <table class="adding-form">
				<tr><th><input type="number" name="z_czlonek_data" min="1" max="2100" value="<?php
														if (isset($_SESSION['z_czlonek_data']))
														{
															echo $_SESSION['z_czlonek_data'];
															unset($_SESSION['z_czlonek_data']);
														}
														?>"></th>
														
		<td><input type="submit" value="Dodaj rok zakończenia współpracy">
        
                                                        <?php
															if (isset($_SESSION['err_czlonek_data']))
															{
																echo '<div class="error">'.$_SESSION['err_czlonek_data'].'</div>';
																unset($_SESSION['err_czlonek_data']);
															}
														?></td></tr>
            <tr><th></th><td><a href="zespol_wyswietl.php?id_z=<?php echo $_GET['id_z']; ?>">Wróć do strony zespołu</a></td></tr>    
        </table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>