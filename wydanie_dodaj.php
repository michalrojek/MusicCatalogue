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
	<h2 class="heading-centre">Dodawanie wydania</h2>
	<form method="post" action="wydanie_dodawanie.php">
        <?php
            if (isset($_SESSION['udanedodanie']))
            {
                echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie wydania przebiegło pomyślnie!</span></h3>';
                unset($_SESSION['udanedodanie']);
            }
        ?>
		<table class="adding-form">
			<tr><th>Nazwa wydania: </th><td><input type="text" name="wydanie_nazwa" value="<?php
			if (isset($_SESSION['wydanie_nazwa']))
			{
				echo $_SESSION['wydanie_nazwa'];
				unset($_SESSION['wydanie_nazwa']);
			}
			?>">
			
			<?php
			if (isset($_SESSION['err_wydanie']))
			{
				echo '<div class="error">'.$_SESSION['err_wydanie'].'</div>';
				unset($_SESSION['err_wydanie']);
			}
			?><td></tr>
			
			<tr><th></th><td><input type="submit" value="Dodaj wydanie"><td></tr>
		</table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>
