<?php
	require_once('zalogowano.php');
	
	if(isset($_POST['typ_nazwa']))
	{
		$wszystko_ok=true;
		if(!(strlen($_POST["typ_nazwa"]) > 0 && strlen(trim($_POST["typ_nazwa"])) == 0)&&$_POST["typ_nazwa"]!="")
		{
			$typ_nazwa=mysqli_real_escape_string($link,$_POST["typ_nazwa"]);
			$typ_nazwa=htmlspecialchars($typ_nazwa);
			
            if(!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšśŚžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'-]+$/", $typ_nazwa))
            {
                $wszystko_ok=false;
                $_SESSION['err_typ']="Nazwa typu albumu może składać się jedynie z liter!"; 
            }
            else
            {
                $q1=$link->query("select nazwa_typu_albumu from typ_albumu where nazwa_typu_albumu LIKE '{$typ_nazwa}'");
                $row1=$q1->fetch_assoc();

                if(count($row1)!=0)
                {
                    $wszystko_ok=false;
                    $_SESSION['err_typ']="Taki typ albumu już istnieje!";
                }
            }
        }
		else
		{
			$wszystko_ok=false;
			$_SESSION['err_typ']="Niewłaściwa nazwa typu albumu!";
		}
		
		if($wszystko_ok)
		{
			try
			{
				$rezultat1=$link->query("INSERT INTO typ_albumu VALUES (NULL, '$typ_nazwa')");
				if (!$rezultat1) throw new Exception($link->error);
				$_SESSION['udanedodanie']=true;
				header('location:typ_dodaj.php');exit;
			}
			catch(Exception $e)
			{
				echo '<br />Informacja developerska: '.$e;
			}
		}
		else
		{
			$_SESSION['typ_nazwa']=$_POST['typ_nazwa'];
			header('location:typ_dodaj.php');exit;
		}
	}
	else
	{
		header('location:typ_dodaj.php');exit;
	}
?>