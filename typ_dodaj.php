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
	<h2 class="heading-centre">Dodawanie typu</h2>
	<form method="post" action="typ_dodawanie.php">
        <?php
            if (isset($_SESSION['udanedodanie']))
            {
                echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie typu albumu przebiegło pomyślnie!</span></h3>';
                unset($_SESSION['udanedodanie']);
            }
        ?>
		<table class="adding-form">
			<tr><th>Nazwa typu: </th><td><input type="text" name="typ_nazwa" value="<?php
			if (isset($_SESSION['typ_nazwa']))
			{
				echo $_SESSION['typ_nazwa'];
				unset($_SESSION['typ_nazwa']);
			}
			?>">
			
			<?php
			if (isset($_SESSION['err_typ']))
			{
				echo '<div class="error">'.$_SESSION['err_typ'].'</div>';
				unset($_SESSION['err_typ']);
			}
			?><td></tr>
			
			<tr><th></th><td><input type="submit" value="Dodaj typ albumu"><td></tr>
		</table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>