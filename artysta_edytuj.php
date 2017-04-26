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

        if (isset($_SESSION['udanedodanie']))
        {
            echo '<h3 class="heading-centre"><span style="color:green;">Edytowanie danych artysty przebiegło pomyślnie!</span></h3>';
            unset($_SESSION['udanedodanie']);
        }

    ?>
	<?php
		if(isset($_GET['id_a']))
		{
			$id_a=$_GET['id_a'];
			
			if(isset($_POST['imie_artysty'])&&isset($_POST['nazwisko_artysty'])&&isset($_POST['pseudonim_artysty']))
			{
				$wszystko_ok=true;
				
				if(!(strlen($_POST['imie_artysty']) > 0 && strlen(trim($_POST['imie_artysty'])) == 0)&&$_POST['imie_artysty']!="")
				{
					$a_imie=mysqli_real_escape_string($link,$_POST["imie_artysty"]);
					$a_imie=htmlspecialchars($a_imie);
				}
				else
				{
						$wszystko_ok=false;
						$_SESSION['err_i']="Podano błędne dane!";
				}
                
				if(!(strlen($_POST['nazwisko_artysty']) > 0 && strlen(trim($_POST['nazwisko_artysty'])) == 0)&&$_POST['nazwisko_artysty']!="")
				{
					$a_nazwisko=mysqli_real_escape_string($link,$_POST["nazwisko_artysty"]);
					$a_nazwisko=htmlspecialchars($a_nazwisko);
				}
				else
				{
						$wszystko_ok=false;
						$_SESSION['err_n']="Podano błędne dane!";
				}
                
				if(!(strlen($_POST['pseudonim_artysty']) > 0 && strlen(trim($_POST['pseudonim_artysty'])) == 0)&&$_POST['pseudonim_artysty']!="")
				{
					$a_pseudonim=mysqli_real_escape_string($link,$_POST["pseudonim_artysty"]);
					$a_pseudonim=htmlspecialchars($a_pseudonim);
				}
				else
				{
						$a_pseudonim="";
				}
				
				//SPRAWDZ CZY JUZ JEST TAKI ARTYSTA - WZOR 'IMIE NAZWISKO' LUB 'IMIE PSEUDONIM NAZWISKO'
				if($wszystko_ok)
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
				
				if($wszystko_ok)
				{
                    try
                    {
                        $rezultat2=$link->query("update artysta set imie_artysty='$a_imie', nazwisko_artysty='$a_nazwisko', pseudonim_artysty='$a_pseudonim' where id_artysty='$id_a'");
                        if(!$rezultat2) throw new Exception($link->error);
                        $_SESSION['udanedodanie']=true;
                        header("location:artysta_edytuj.php?id_a=".$id_a);
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
				}
			}
			
			$q1=mysqli_fetch_assoc(mysqli_query($link,"select * from artysta where id_artysty='$id_a'"));
			if(empty($q1))
			{
				echo "Nie ma artysty o takim identyfikatorze w bazie danych!";
			}
			else
			{
				$_SESSION['imie_artysty']=$q1['imie_artysty'];
				$_SESSION['nazwisko_artysty']=$q1['nazwisko_artysty'];
				$_SESSION['pseudonim_artysty']=$q1['pseudonim_artysty'];
			}
		}
		else
		{
			header("location:artysci.php");
		}

	?>
    <h2 class="heading-centre">Edycja danych artysty</h2>
	<form method="post">
        <table class="adding-form">
            <tr><th>Imię artysty:</th><td><input type="text" name="imie_artysty" value="<?php 
                                                                                            if (isset($_SESSION['imie_artysty']))
                                                                                            {
                                                                                                echo $_SESSION['imie_artysty'];
                                                                                                unset($_SESSION['imie_artysty']);
                                                                                            } 
                                                                                        ?>">
                
                                                                                        <?php
                                                                                            if (isset($_SESSION['err_i']))
                                                                                                {
                                                                                                    echo '<div class="error">'.$_SESSION['err_i'].'</div>';
                                                                                                    unset($_SESSION['err_i']);
                                                                                                }
                                                                                        ?></td></tr>
            
		<tr><th>Nazwisko artysty:</th><td><input type="text" name="nazwisko_artysty" value="<?php 
                                                                                                if (isset($_SESSION['nazwisko_artysty']))
                                                                                                {
                                                                                                    echo $_SESSION['nazwisko_artysty'];
                                                                                                    unset($_SESSION['nazwisko_artysty']);
                                                                                                } 
                                                                                            ?>">
            
                                                                                            <?php
                                                                                                if (isset($_SESSION['err_n']))
                                                                                                    {
                                                                                                        echo '<div class="error">'.$_SESSION['err_n'].'</div>';
                                                                                                        unset($_SESSION['err_n']);
                                                                                                    }
                                                                                            ?></td></tr>
            
		<tr><th>Pseudonim artysty:</th><td><input type="text" name="pseudonim_artysty" value="<?php 
                                                                                                if (isset($_SESSION['pseudonim_artysty']))
                                                                                                {
                                                                                                    echo $_SESSION['pseudonim_artysty'];
                                                                                                    unset($_SESSION['pseudonim_artysty']);
                                                                                                } 
                                                                                            ?>"></td></tr>
		<tr><th></th><td><input type="submit" value="Edytuj artystę"></td></tr>
        
        <?php
            if (isset($_SESSION['err_artysta']))
            {
                echo '<tr><th></th><td class="error">'.$_SESSION['err_artysta'].'</td></tr>';
                unset($_SESSION['err_artysta']);
            }
        ?>
            <tr><th></th><td><a href="artysta_wyswietl.php?id_a=<?php echo $_GET['id_a']; ?>">Wróć do strony artysty</a></td></tr>
        </table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>