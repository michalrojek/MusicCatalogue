<?php
	require_once('sprawdz_logowanie.php');
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body style="padding-top: 0.1rem;">
    
	<?php
		
		foreach($_POST as $k=>$v) {$_POST[$k]=mysqli_real_escape_string($link,$v);}
		foreach($_SERVER as $k=>$v) {$_SERVER[$k]=mysqli_real_escape_string($link,$v);}
		
		if(isset($_POST['login']))
        {	
			$q1=mysqli_fetch_assoc(mysqli_query($link,"select salt from uzytkownik where login='{$_POST['login']}'"));
			$saltq=$q1['salt'];
			$pass=sha1($_POST['password'].$saltq);
			$q=mysqli_fetch_assoc(mysqli_query($link,"select count(*) cnt, id_uzytkownika from uzytkownik where login='{$_POST['login']}' and haslo='{$pass}';"));
			
            if($q['cnt'])
            {
                $id=md5(rand(-10000,10000) . microtime()) . md5(crc32(microtime()) . 
                $_SERVER['REMOTE_ADDR']);
                mysqli_query($link,"delete from sesja where ID_users='$q[id_uzytkownika]';");
                mysqli_query($link,"insert into sesja (ID_users,id,ip,web) values 
                ('$q[id_uzytkownika]','$id','$_SERVER[REMOTE_ADDR]','$_SERVER[HTTP_USER_AGENT]')");
                if(!mysqli_errno($link))
                {
                    setcookie("id",$id);
                    $_SESSION['err_login']="Zalogowano pomyślnie!";
                    $q2=mysqli_query($link,"insert into historia_logowan (id_uzytkownika,data_logowania) values ('$q[id_uzytkownika]',now())");
                    if(!$q2)
                    {
                        $_SESSION['err_login']="Błąd podczas logowania!";
                    }
                    else
                    {
                        header ("location:strona_glowna.php");
                    }
                }
                else 
                {
                    $_SESSION['err_login']="Błąd podczas logowania!";
                }
            }
            else
            {
                $_SESSION['err_login']="Błąd logowania!";
			}
		}
		
	?>
    <noscript>
      <h3 class="heading-centre">Do pełnej funkcjonalności strony potrzebujesz włączonej obsługi skryptów.
      Tu znajdziesz <a href="http://www.enable-javascript.com/pl/" target="_blank">
      instrukcje, które pozwolą Ci włączyć skrypty w Twojej przeglądarce</a>.</h3>
    </noscript>
    <h2 class="heading-centre">Katalog muzyczny - logowanie</h2>
    <form method="post">
		<table class="login">
			<tr><th>Login:</th><td><input type=text name="login"></td></tr>
			<tr><th>Hasło:</th><td><input type=password name="password"></td></tr>
			<tr><th></th><td><input type=submit value="Zaloguj się"></td></tr>
            <?php
                if(isset($_SESSION['err_login']))
                {
                    echo '<tr class="error"><th></th><td>'.$_SESSION['err_login'].'</td></tr>';
                    unset($_SESSION['err_login']);
                }
            ?>
            <tr><th></th><td><a href="rejestracja.php" id="reg-link">Zarejestruj się</a></td></tr>
		</table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>