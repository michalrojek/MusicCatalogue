<?php
	require_once('zalogowano.php');
?>
	<?php	
			$wszystko_ok=true;
			
			if(!(strlen($_POST['a_imie']) > 0 && strlen(trim($_POST['a_imie'])) == 0)&&$_POST['a_imie']!="")
			{
				$a_imie=mysqli_real_escape_string($link,$_POST["a_imie"]);
				$a_imie=htmlspecialchars($a_imie);
                if(!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšśŚžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'-]+$/", $a_imie))
                {
                    $wszystko_ok=false;
					$_SESSION['err_imie']="Imię artysty może składać się jedynie z liter!"; 
                }
			}
			else
			{
					$wszystko_ok=false;
					$_SESSION['err_imie']="Podaj imię artysty!";
			}
			if(!(strlen($_POST['a_nazwisko']) > 0 && strlen(trim($_POST['a_nazwisko'])) == 0)&&$_POST['a_nazwisko']!="")
			{
				$a_nazwisko=mysqli_real_escape_string($link,$_POST["a_nazwisko"]);
				$a_nazwisko=htmlspecialchars($a_nazwisko);
                if(!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšśŚžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'-]+$/", $a_nazwisko))
                {
                    $wszystko_ok=false;
					$_SESSION['err_nazwisko']="Nazwisko artysty może składać się jedynie z liter!"; 
                }
			}
			else
			{
					$wszystko_ok=false;
					$_SESSION['err_nazwisko']="Podaj nazwisko artysty!";
			}
			if(!(strlen($_POST['a_pseudonim']) > 0 && strlen(trim($_POST['a_pseudonim'])) == 0)&&$_POST['a_pseudonim']!="")
			{
				$a_pseudonim=mysqli_real_escape_string($link,$_POST["a_pseudonim"]);
				$a_pseudonim=htmlspecialchars($a_pseudonim);
			}
			else
			{
					$a_pseudonim="";
			}
			
			//SPRAWDZ CZY JUZ JEST TAKI ARTYSTA - WZOR 'IMIE NAZWISKO' LUB 'IMIE PSEUDONIM NAZWISKO'
			
			if($a_pseudonim!="")
			{
				$item=$a_imie.' '.$a_pseudonim.' '.$a_nazwisko;
				$q1=$link->query("select * from artysta where concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI like '$item'");
				$row1=$q1->fetch_assoc();
				if(count($row1)>0)
				{
					$wszystko_ok=false;
					$_SESSION['err_artysta']="W bazie już istnieje taki artysta!";
				}
			}
			else
			{
				$item=$a_imie.' '.$a_nazwisko;
				$q1=$link->query("select * from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI like '$item'");
				$row1=$q1->fetch_assoc();
				if(count($row1)>0)
				{
					$wszystko_ok=false;
					$_SESSION['err_artysta']="W bazie już istnieje taki artysta!";
				}
			}
			
			if(!(strlen($_POST['a_kraj']) > 0 && strlen(trim($_POST['a_kraj'])) == 0)&&$_POST['a_kraj']!="")
			{
				$a_kraj=mysqli_real_escape_string($link,$_POST["a_kraj"]);
				$a_kraj=htmlspecialchars($a_kraj);
                if(!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšśŚžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'-]+$/", $a_kraj))
                {
                    $wszystko_ok=false;
					$_SESSION['err_kraj']="Nazwa państwa może składać się jedynie z liter!"; 
                }
			}
			else
			{
					$wszystko_ok=false;
					$_SESSION['err_kraj']="Podaj kraj pochodzenia artysty!";
			}
			
			$a_data=str_replace("-","",$_POST['a_data']);
			if(checkdate((int)substr($a_data,4,2),(int)substr($a_data,6),(int)substr($a_data,0,4)))
			{
                $today = date("Y-m-d");
                $todaytime=strtotime($today);
                $a_datatime=strtotime($_POST['a_data']);
                if($todaytime<$a_datatime)
                {
                    $wszystko_ok=false;
					$_SESSION['err_data']="Data urodzenia artysty nie może być z przyszłości!"; 
                }
			}
			else
			{
					$wszystko_ok=false;
					$_SESSION['err_data']="Podaj dobrą datę urodzenia artysty!";
			}
			
			if($wszystko_ok)
			{	
				try{
				if ($link->query("INSERT INTO artysta VALUES (NULL, '$a_imie', '$a_nazwisko',  '$a_pseudonim', '$a_kraj', '$a_data')"))
							{
								$_SESSION['udanedodanie']=true;
								header('Location: artysta_dodaj.php');
							}
							else
							{
								throw new Exception($link->error);
							}
							$link->close();
				}catch(Exception $e)
				{
					echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
					echo '<br />Informacja developerska: '.$e;
				}
			}
			else
			{
				$_SESSION['a_imie']=$_POST['a_imie'];
				$_SESSION['a_nazwisko']=$_POST['a_nazwisko'];
				$_SESSION['a_pseudonim']=$_POST['a_pseudonim'];
				$_SESSION['a_kraj']=$_POST['a_kraj'];
				$_SESSION['a_data']=$_POST['a_data'];
				header('Location: artysta_dodaj.php');
			}
	?>