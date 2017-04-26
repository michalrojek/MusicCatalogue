<?php
	$nick = $_POST['nick'];
	$email = $_POST['email'];
	$haslo1 = $_POST['haslo1'];
	$haslo2 = $_POST['haslo2'];
	$regulamin = $_POST['regulamin'];
	$wszystko_OK = true;
	
	if(!$nick)
	{
		echo "Wprowadz nick!</br>";
		$wszystko_OK=false;
	}
	else
	if ((strlen($nick)<3) || (strlen($nick)>20))
	{
		echo "Nick musi posiadać od 3 do 20 znaków!</br>";
		$wszystko_OK=false;
	}
	else
	if (ctype_alnum($nick)==false)
	{
		echo "Nick może składać się tylko z liter i cyfr (bez polskich znaków)</br>";
		$wszystko_OK=false;
	}
		
	
	if(!$email)
	{
		echo 'Wprowadz email!</br>';
		$wszystko_OK=false;
	}
	else
	{
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			echo 'Podaj poprawny adres e-mail!</br>';
			$wszystko_OK=false;
		}
	}
	
	if(!$haslo1)
	{
		echo "Wprowadz haslo!</br>";
		$wszystko_OK=false;
	}
	else
	if((strlen($haslo1)<8) || (strlen($haslo1)>20))
	{
		echo 'Hasło musi posiadać od 8 do 20 znaków!</br>';
		$wszystko_OK=false;
	}
	else
	if(!$haslo2)
	{
		echo 'Wprowadz ponownie hasło!</br>';
		$wszystko_OK=false;
	}
	else
	if($haslo1!=$haslo2)
	{
		echo 'Podane hasła nie są identyczne!</br>';
		$wszystko_OK=false;
	}

	if($regulamin=="false")
	{
		echo 'Potwierdź akceptację regulaminu!</br>';
		$wszystko_OK=false;
	}

	if($wszystko_OK)
	{
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try 
		{
			$link = new mysqli($host, $db_user, $db_password, $db_name);
			if ($link->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$rezultat = $link->query("SELECT id_uzytkownika FROM uzytkownik WHERE email='$email'");
				
				if (!$rezultat) throw new Exception($link->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					echo 'Istnieje już konto przypisane do tego adresu e-mail!</br>';
					$wszystko_OK=false;
				}		

				$rezultat = $link->query("SELECT id_uzytkownika FROM uzytkownik WHERE login='$nick'");
				
				if (!$rezultat) throw new Exception($link->error);
				
				$ile_takich_nickow = $rezultat->num_rows;
				if($ile_takich_nickow>0)
				{
					echo 'Istnieje już użytkownik o takim nicku! Wybierz inny.</br>';
					$wszystko_OK=false;
				}
				
				if ($wszystko_OK==true)
				{	
                    $haslo=mysqli_real_escape_string($link,$haslo1);
				    $haslo=htmlspecialchars($haslo);	
					$salt=substr(sha1(rand(1,10000)),0,20);
					$passwordtohash=$haslo.$salt;
					$haslo_hash=sha1($passwordtohash);
					
					if ($link->query("INSERT INTO uzytkownik VALUES (NULL, '$nick', '$haslo_hash', '$email', 1, '$salt')"))
					{
						echo "udanarejestracja";
					}
					else
					{
						throw new Exception($link->error);
					}		
				}
				
				$link->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
	}
?>