<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.1.1.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" type="text/javascript"></script>
</head>
<body>
    <?php

        require_once('menu.php');

        if($basic_q2['id_typu_uzytkownika']==1)
        {
            header('location:strona_glowna.php'); exit;
        }

        if (isset($_SESSION['udanedodanie']))
        {
            echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie artysty do zespolu przebieglo pomyslnie!</span></h3>';
            unset($_SESSION['udanedodanie']);
        }

    ?>
	<?php
		if(isset($_GET['id_z'])&&is_numeric($_GET['id_z']))
		{
			$wszystko_ok=true;
			if(isset($_POST['z_czlonek'])&&isset($_POST['z_czlonek_data']))
			{
				
				if(!(strlen($_POST['z_czlonek']) > 0 && strlen(trim($_POST['z_czlonek'])) == 0)&&$_POST['z_czlonek']!="")
				{
					$z_czlonek=mysqli_real_escape_string($link,$_POST['z_czlonek']);
					$z_czlonek=htmlspecialchars($z_czlonek);
					$q2=$link->query("select * from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonek'
					or concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonek'");
					$row2=$q2->fetch_assoc();
				
					if(count($row2)==0)
					{
						$wszystko_ok=false;
						$_SESSION['err_czlonek']="Nie ma takiego artysty w bazie danych! ";
					}
				}
				else
				{
					$wszystko_ok=false;
					$_SESSION['err_czlonek']="Podaj imię i nazwisko lub pseudonim członka zespołu! ";
					$z_czlonek=mysqli_real_escape_string($link,$_POST['z_czlonek']);
					$z_czlonek=htmlspecialchars($z_czlonek);
				}
				
				if($_POST['z_czlonek_data']=="")
				{
					$wszystko_ok=false;
					$_SESSION['err_czlonek_data']="Podaj datę rozpoczęcia współpracy!";
				}
				else
				{
					$z_czlonek_data=$_POST['z_czlonek_data'];
				}
				
				if($wszystko_ok)
				{
					$id_z=$_GET['id_z'];
					$rezultat6=mysqli_fetch_assoc(mysqli_query($link,"select * from zespol where id_zespolu='{$id_z}'"));
                    if(!$rezultat6)
                    {
                        throw new Exception($link->error);
                    }
                    
					try
					{
                        $wszystko_ok2=true;
						$rezultat2 =mysqli_fetch_assoc(mysqli_query($link,"select *,YEAR(data_ur_artysty) as rok from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonek'
						or concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$z_czlonek'"));
						if(!$rezultat2)
						{
							throw new Exception($link->error);
						}
						else
						{
							$a_id=$rezultat2['id_artysty'];
                            
                            $rezultat4=mysqli_query($link,"select * from artysta_to_zespol where id_artysty='{$a_id}' and id_zespolu='{$id_z}'");
                            if(!$rezultat4)
                            {
                                throw new Exception($link->error);
                            }
                            else
                            {
                                if($rezultat4->num_rows>0)
                                {
                                    while($row = $rezultat4->fetch_assoc())
                                    {
                                        if($row['rok_zakon_wspolpracy']==0)
                                        {
                                            $wszystko_ok2=false;
                                            $_SESSION['err_czlonek']="Ten artysta jest obecnie w zespole!";
                                        }
                                        else if($row['rok_zakon_wspolpracy']>$z_czlonek_data)
                                        {
                                            $wszystko_ok2=false;
                                            $_SESSION['err_czlonek_data']="Rok rozpoczęcia nowej współpracy nie może być mniejszy niż rok zakończenia ostatniej współpracy dla danego artysty!";
                                        }
                                        else if($row['rok_zakon_wspolpracy']==$z_czlonek_data)
                                        {
                                            $wszystko_ok2=false;
                                            $_SESSION['err_czlonek_data']="W bazie już znajduje się rekord informujący o nawiązaniu współpracy dla tego artysty w podanym roku!";
                                        }
                                    }
                                }
                                else if($z_czlonek_data<$rezultat6['rok_rozp_dzial'])
                                {
                                    $wszystko_ok2=false;
                                    $_SESSION['err_czlonek_data']="Rok rozpoczęcia współpracy nie może być mniejszy niż rok rozpoczęcia działalności zespołu!";
                                }
                                else if($rezultat2['rok']>$z_czlonek_data)
                                {
                                    $wszystko_ok2=false;
                                    $_SESSION['err_czlonek_data']="Data rozpoczęcia współpracy nie moze być mniejsza niż data urodzenia artysty!";
                                }
                            }
                            
                            if($wszystko_ok2)
                            {    
                                $rezultat3=mysqli_query($link,"INSERT INTO artysta_to_zespol VALUES ('$id_z', '$a_id','$z_czlonek_data',0)");
                                if (!$rezultat3)
                                {
                                    throw new Exception($link->error);
                                }
                                else
                                {
                                    $_SESSION['udanedodanie']=true;
                                    header('Location:zespol_dodaj_cz.php?id_z='.$_GET['id_z']);exit;
                                }
                            }
                            else
                            {
                                $_SESSION['z_czlonek']=$_POST['z_czlonek'];
                                $_SESSION['z_czlonek_data']=$_POST['z_czlonek_data'];
                            }
						}			
					}
					catch(Exception $e)
					{
						echo '<br />Informacja developerska: '.$e;
					}
				}
				else
				{
					$_SESSION['z_czlonek']=$_POST['z_czlonek'];
					$_SESSION['z_czlonek_data']=$_POST['z_czlonek_data'];
					header('location:zespol_dodaj_cz.php?id_z='.$_GET['id_z']);exit;
				}
			}
			
		}
		else
		{
			header('location:zespoly.php');exit;
		}
	?>
	<form method="post">
        <h2 class="heading-centre">Dodawanie artysty do zespołu</h2>
        <table class="adding-form">
			<tr><th>Imię i naziwsko: </th><td><input type="text" name="z_czlonek" class="artysta" value="<?php
														if (isset($_SESSION['z_czlonek']))
														{
															echo $_SESSION['z_czlonek'];
															unset($_SESSION['z_czlonek']);
														}
														?>">
                
                                                        <?php
															if (isset($_SESSION['err_czlonek']))
															{
																echo '<div class="error">'.$_SESSION['err_czlonek'].'</div>';
																unset($_SESSION['err_czlonek']);
															}
														?></td></tr>
                
            <tr><th>Rok rozpoczęcia współpracy: </th><td><input type="number" name="z_czlonek_data" min="1" max="2100" value="<?php
														if (isset($_SESSION['z_czlonek_data']))
														{
															echo $_SESSION['z_czlonek_data'];
															unset($_SESSION['z_czlonek_data']);
														}
														?>">
                
                                                        <?php
															if (isset($_SESSION['err_czlonek_data']))
															{
																echo '<div class="error">'.$_SESSION['err_czlonek_data'].'</div>';
																unset($_SESSION['err_czlonek_data']);
															}
														?></td></tr>
            <tr><th></th><td><input type="submit" value="Dodaj artystę do zespołu"></td></tr>
            <tr><th></th><td><a href="zespol_wyswietl.php?id_z=<?php echo $_GET['id_z']; ?>">Wróć do strony zespołu</a></td></tr>
        </table>
    </form>
    <?php
        require_once("stopka.php");
    ?>
	<script>
		$(document).ready(function(){
			function run() {
				$( ".artysta" ).autocomplete({
					source: 'search_artysta.php'
				});
			}
		
			run();
		});
	</script>
</body>