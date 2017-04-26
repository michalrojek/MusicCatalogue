<?php
	require_once('zalogowano.php');
?>
	<?php
		$wszystko_ok=true;
		
		if(!(strlen($_POST['a_tytul']) > 0 && strlen(trim($_POST['a_tytul'])) == 0)&&$_POST['a_tytul']!="")
		{
			$a_tytul=mysqli_real_escape_string($link,$_POST['a_tytul']);
			$a_tytul=htmlspecialchars($a_tytul);
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_tytul']="Podaj tytuł albumu!";
		}
		
		if(!(strlen($_POST['a_autor']) > 0 && strlen(trim($_POST['a_autor'])) == 0)&&$_POST['a_autor']!="")
		{
			$a_autor=mysqli_real_escape_string($link,$_POST['a_autor']);
			$a_autor=htmlspecialchars($a_autor);
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_autor']="Podaj autora albumu!";
		}
		
		$q1=$link->query("select * from artysta where concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$a_autor'
				or concat(imie_artysty,' ',pseudonim_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '$a_autor'");
		
		$row1=$q1->fetch_assoc();
		if(count($row1)==0)
		{
			$q1=$link->query("select * from zespol where nazwa_zespolu LIKE '$a_autor'");
			$row1=$q1->fetch_assoc();
			if(count($row1)==0)
			{
				$wszystko_ok=false;
				$_SESSION['err_autor']="Nie ma takiego artysty/zespolu!";
			}
			else
			{
				$a_id_z=$row1['id_zespolu'];
			}
		}
		else
		{
			$a_id_a=$row1['id_artysty'];
		}
		
		$a_data=str_replace("-","",$_POST['a_data']);
		if(checkdate((int)substr($a_data,4,2),(int)substr($a_data,6),(int)substr($a_data,0,4)))
		{
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_data']="Podaj dobrą datę powstania albumu!";
		}
		
		$i=0;
		foreach($_POST['mygat'] as $item)
		{
			if(!(strlen($item) > 0 && strlen(trim($item)) == 0)&&$item!="")
			{
				$a_gatunek[$i]=mysqli_real_escape_string($link,$item);
				$a_gatunek[$i]=htmlspecialchars($a_gatunek[$i]);
				$i++;	
			}
			else
			{
				$wszystko_ok=false;
				$_SESSION['err_gatunek'][$i]="Podaj nazwę gatunku! ";
				$a_gatunek[$i]=mysqli_real_escape_string($link,$item);
				$a_gatunek[$i]=htmlspecialchars($a_gatunek[$i]);
				$i++;
			}
		}
		
		$i=0;
		foreach($a_gatunek as $item)
		{
			if(!(strlen($item) > 0 && strlen(trim($item)) == 0)&&$item!="")
			{
				$q1=$link->query("select * from gatunek where nazwa_gatunku LIKE '$item'");
				$row1=$q1->fetch_assoc();
				if(count($row1)==0)
				{
					$wszystko_ok=false;
					$_SESSION['err_gatunek'][$i]="Nie ma takiego gatunku! ";
				}
			}
			$i++;
		}
		
		if(isset($_POST['a_type']))
		{
			$a_typ=$_POST['a_type'];
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_typ']="Wybierz typ albumu!";
		}
		
		if(isset($_POST['a_wyd']))
		{
			$a_wydanie=$_POST['a_wyd'];
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_wydanie']="Wybierz wydania albumu!";
		}
		
		$i=0;
		foreach($_POST['piosenki'] as $item)
		{
			if(!(strlen($item) > 0 && strlen(trim($item)) == 0)&&$item!="")
			{
				$a_piosenki[$i]=mysqli_real_escape_string($link,$item);
				$a_piosenki[$i]=htmlspecialchars($a_piosenki[$i]);	
			}
			else
			{
				$wszystko_ok=false;
				$_SESSION['err_piosenki'][$i]="Podaj tytuł piosenki! ";
				$a_piosenki[$i]=mysqli_real_escape_string($link,$item);
				$a_piosenki[$i]=htmlspecialchars($a_piosenki[$i]);
			}
			$i++;
		}
		$i=0;
		foreach($_POST['czas'] as $item)
		{
			if($item=="")
			{
				$wszystko_ok=false;
				$_SESSION['err_piosenki_czas'][$i]="Podaj czas trwania piosenki! ";
				$a_czas[$i]="";
			}
			else
			{
				$a_czas[$i]=$item;
			}
			$i++;
		}

		$czas="00:00:00";
		foreach($a_czas as $item)
		{
			$secs = strtotime($item)-strtotime("00:00:00");
			$czas = date("H:i:s",strtotime($czas)+$secs);
		}
		$czas_a=date("H",strtotime($czas)).date("i",strtotime($czas)).date("s",strtotime($czas));
		
		if($a_typ=="single"&&(count($a_piosenki)>2||(int)substr($czas_a,0,2)>0||(int)substr($czas_a,2,2)>10))
		{
			$wszystko_ok=false;
			$_SESSION['err_typ_czas']="Album typu single może mieć maksymalnie 2 piosenki i trwać 10 minut! ";
		}
		
		if($a_typ=="ep"&&(count($a_piosenki)>5||(int)substr($czas_a,0,2)>0||(int)substr($czas_a,2,2)>30))
		{
			$wszystko_ok=false;
			$_SESSION['err_typ_czas']="Album typu extended play może mieć maksymalnie 5 piosenek i trwać 30 minut! ";
		}
		
		if($wszystko_ok)
		{
			$wszystko_ok2=true;
			try{
				if($rezultat2=$link->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED;"))
				{
					
				}
				else
				{
					throw new Exception($link->error);
				}
				
				if($rezultat2=$link->query("START TRANSACTION;"))
				{
					
				}
				else
				{
					throw new Exception($link->error);
				}
				
				$q2=mysqli_fetch_assoc(mysqli_query($link,"select id_typu_albumu from typ_albumu where nazwa_typu_albumu='$a_typ'"));
				if(!$q2)
				{	
					$wszystko_ok2=false;			
					if($rezultat2=$link->query("ROLLBACK;"))
					{
						
					}
					else
					{
						throw new Exception($link->error);
					}
					throw new Exception($link->error);
				}
				
				$t_id=$q2['id_typu_albumu'];
				$a_p_count=count($a_piosenki);
				
				$rezultat1=$link->query("INSERT INTO album VALUES (NULL, '$a_tytul', '$a_data', '$czas_a', '$a_p_count', '$t_id')");
				if (!$rezultat1)
				{			
					$wszystko_ok2=false;
					if($rezultat2=$link->query("ROLLBACK;"))
					{
						
					}
					else
					{
						throw new Exception($link->error);
					}
					throw new Exception($link->error);
				}
					
				$q1=mysqli_fetch_assoc(mysqli_query($link,"select max(id_albumu) as id_albumu from album"));
				if(!$q1)
				{			
					$wszystko_ok2=false;
					if($rezultat2=$link->query("ROLLBACK;"))
					{
						
					}
					else
					{
						throw new Exception($link->error);
					}
					throw new Exception($link->error);
				}	
				
				$al_id=$q1['id_albumu'];
				
				if(isset($a_id_a))
				{
					$rezultat3=$link->query("INSERT INTO album_to_artysta VALUES ('$al_id','$a_id_a')");
					if (!$rezultat3)
					{		
						$wszystko_ok2=false;
						if($rezultat2=$link->query("ROLLBACK;"))
						{

						}
						else
						{
							throw new Exception($link->error);
						}
						throw new Exception($link->error);
					}
				}
				if(isset($a_id_z))
				{
					$rezultat3=$link->query("INSERT INTO album_to_zespol VALUES ('$al_id','$a_id_z')");
					if (!$rezultat3)
					{				
						$wszystko_ok2=false;
						if($rezultat2=$link->query("ROLLBACK;"))
						{

						}
						else
						{
							throw new Exception($link->error);
						}
						throw new Exception($link->error);
					}
				}				
					
				$x=0;
				if(isset($a_id_a)||isset($a_id_z))
				{
					foreach($a_piosenki as $item)
					{
						if($item != "")
						{
							$n_piosenki=$item;
							$c_piosenki=date("H",strtotime($a_czas[$x])).date("i",strtotime($a_czas[$x])).date("s",strtotime($a_czas[$x]));
							$x++;
							
							if (!($link->query("INSERT INTO piosenka VALUES (NUll, '$n_piosenki', '$c_piosenki')")))
							{
								$wszystko_ok2=false;
								if($rezultat2=$link->query("ROLLBACK;"))
								{

								}
								else
								{
									throw new Exception($link->error);
								}
								throw new Exception($link->error);
							}
							
							$rezultat2 =mysqli_fetch_assoc(mysqli_query($link,"select max(id_piosenki) as id_p from piosenka"));
							if(!$rezultat2)
							{
								$wszystko_ok2=false;
								if($rezultat2=$link->query("ROLLBACK;"))
								{

								}
								else
								{
									throw new Exception($link->error);
								}
								throw new Exception($link->error);
							}
							else
							{
								$p_id=$rezultat2['id_p'];
								$rezultat4=$link->query("INSERT INTO album_to_piosenka VALUES ('$al_id', '$p_id')");
								if (!$rezultat4)
								{
									$wszystko_ok2=false;
									if($rezultat2=$link->query("ROLLBACK;"))
									{

									}
									else
									{
										throw new Exception($link->error);
									}
									throw new Exception($link->error);
								}				
							}
						}				
					}
					
					
					foreach($a_wydanie as $item)
					{
						if($item != "")
						{
							$rezultat2 =mysqli_fetch_assoc(mysqli_query($link,"select id_wydania from wydanie where nazwa_wydania='$item'"));
							if(!$rezultat2)
							{
								$wszystko_ok2=false;
								if($rezultat2=$link->query("ROLLBACK;"))
								{
									
								}
								else
								{
									throw new Exception($link->error);
								}
								throw new Exception($link->error);
							}
							else
							{
								$wyd_id=$rezultat2['id_wydania'];
								$rezultat4=$link->query("INSERT INTO album_to_wydanie VALUES ('$al_id', '$wyd_id')");
								if (!$rezultat4)
								{
									$wszystko_ok2=false;
									if($rezultat2=$link->query("ROLLBACK;"))
									{

									}
									else
									{
										throw new Exception($link->error);
									}
									throw new Exception($link->error);
								}
							}
						}				
					}
					
					foreach($a_gatunek as $item)
					{
						if($item != "")
						{
							$rezultat2 =mysqli_fetch_assoc(mysqli_query($link,"select id_gatunku from gatunek where nazwa_gatunku='$item'"));
							if(!$rezultat2)
							{
									$wszystko_ok2=false;
									if($rezultat2=$link->query("ROLLBACK;"))
									{

									}
									else
									{
										throw new Exception($link->error);
									}
									throw new Exception($link->error);
							}

							$gat_id=$rezultat2['id_gatunku'];
							$rezultat4=$link->query("INSERT INTO album_to_gatunek VALUES ('$al_id', '$gat_id')");
								
							if (!$rezultat4)
							{
									$wszystko_ok2=false;
									if($rezultat2=$link->query("ROLLBACK;"))
									{

									}
									else
									{
										throw new Exception($link->error);
									}
									throw new Exception($link->error);
							}
						}				
					}
				}
				
				if(wszystko_ok2)
				{
					if ($rezultat3 = $link->query("COMMIT;"))
					{
						$_SESSION['udanedodanie']=true;
						header('Location:album_dodaj.php');	
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
				
				$link->close();
			}catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
		}
		else
		{
			$_SESSION['a_tytul']=$_POST['a_tytul'];
			$_SESSION['a_autor']=$_POST['a_autor'];
			$_SESSION['a_data']=$_POST['a_data'];
			$_SESSION['mygat']=$_POST['mygat'];
			$_SESSION['a_type']=$_POST['a_type'];
			$_SESSION['a_wyd']=$_POST['a_wyd'];
			$_SESSION['piosenki']=$_POST['piosenki'];
			$_SESSION['czas']=$_POST['czas'];
			header("location:album_dodaj.php");exit;
		}
	?>