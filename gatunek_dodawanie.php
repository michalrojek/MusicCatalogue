<?php
	require_once('zalogowano.php');
	
	if(isset($_POST['gat_nazwa']))
	{
		$wszystko_ok=true;
		if(!(strlen($_POST["gat_nazwa"]) > 0 && strlen(trim($_POST["gat_nazwa"])) == 0)&&$_POST["gat_nazwa"]!="")
		{
			$gat_nazwa=mysqli_real_escape_string($link,$_POST["gat_nazwa"]);
			$gat_nazwa=htmlspecialchars($gat_nazwa);
            
            if(!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšśŚžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\s'-]+$/", $gat_nazwa))
            {
                $wszystko_ok=false;
				$_SESSION['err_gatunek']="Nazwa gatunku może składać się jedynie z liter!"; 
            }
            else
            {
                $q1=$link->query("select nazwa_gatunku from gatunek where nazwa_gatunku LIKE '{$gat_nazwa}'");
                $row1=$q1->fetch_assoc();

                if(count($row1)!=0)
                {
                    $wszystko_ok=false;
                    $_SESSION['err_gatunek']="Taki gatunek już istnieje!";
                }
            }
		}
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_gatunek']="Niewłaściwa nazwa gatunku!";
		}
		
		if($wszystko_ok)
		{
			try
			{
				$rezultat1=$link->query("INSERT INTO gatunek VALUES (NULL, '$gat_nazwa')");
				if (!$rezultat1) throw new Exception($link->error);
				$_SESSION['udanedodanie']=true;
				header('location:gatunek_dodaj.php');exit;
			}
			catch(Exception $e)
			{
				echo '<br />Informacja developerska: '.$e;
			}
		}
		else
		{
			$_SESSION['gat_nazwa']=$_POST['gat_nazwa'];
			header('location:gatunek_dodaj.php');exit;
		}
	}
	else
	{
		header('location:gatunek_dodaj.php');exit;
	}
?>