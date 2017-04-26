<?php
	require_once('zalogowano.php');
	
	if(isset($_POST['wydanie_nazwa']))
	{
		$wszystko_ok=true;
		if(!(strlen($_POST["wydanie_nazwa"]) > 0 && strlen(trim($_POST["wydanie_nazwa"])) == 0)&&$_POST["wydanie_nazwa"]!="")
		{
			$wydanie_nazwa=mysqli_real_escape_string($link,$_POST["wydanie_nazwa"]);
			$wydanie_nazwa=htmlspecialchars($wydanie_nazwa);
			
			$q1=$link->query("select nazwa_wydania from wydanie where nazwa_wydania LIKE '{$wydanie_nazwa}'");
			$row1=$q1->fetch_assoc();
			
			if(count($row1)!=0)
			{
				$wszystko_ok=false;
				$_SESSION['err_wydanie']="Takie wydanie już istnieje!";
			}
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_wydanie']="Niewłaściwa nazwa wydania!";
		}
		
		if($wszystko_ok)
		{
			try
			{
				$rezultat1=$link->query("INSERT INTO wydanie VALUES (NULL, '$wydanie_nazwa')");
				if (!$rezultat1) throw new Exception($link->error);
				$_SESSION['udanedodanie']=true;
				header('location:wydanie_dodaj.php');exit;
			}
			catch(Exception $e)
			{
				echo '<br />Informacja developerska: '.$e;
			}
		}
		else
		{
			$_SESSION['wydanie_nazwa']=$_POST['wydanie_nazwa'];
			header('location:wydanie_dodaj.php');exit;
		}
	}
	else
	{
		header('location:wydanie_dodaj.php');exit;
	}
?>