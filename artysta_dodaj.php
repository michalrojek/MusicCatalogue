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
	<h2 class="heading-centre">Dodawanie artysty</h2>
	<form method="post" action="artysta_dodawanie.php">
        <?php
            if (isset($_SESSION['udanedodanie']))
            {
                echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie artysty przebiegło pomyślnie!</span></h3>';
                unset($_SESSION['udanedodanie']);
            }
        ?>
		<table class="adding-form">
			<tr><th>Imię artysty:</th><td><input type="text" name="a_imie" value="<?php
                                                                                    if (isset($_SESSION['a_imie']))
                                                                                    {
                                                                                        echo $_SESSION['a_imie'];
                                                                                        unset($_SESSION['a_imie']);
                                                                                    }
                                                                                ?>">
                
                                                                                <?php
                                                                                    if (isset($_SESSION['err_imie']))
                                                                                    {
                                                                                        echo '<div class="error">'.$_SESSION['err_imie'].'</div>';
                                                                                        unset($_SESSION['err_imie']);
                                                                                    }
                                                                                ?></td></tr>
			
			<tr><th>Nazwisko artysty:</th><td><input type="text" name="a_nazwisko" value="<?php
                                                                                            if (isset($_SESSION['a_nazwisko']))
                                                                                            {
                                                                                                echo $_SESSION['a_nazwisko'];
                                                                                                unset($_SESSION['a_nazwisko']);
                                                                                            }
                                                                                            ?>">

                                                                                            <?php
                                                                                            if (isset($_SESSION['err_nazwisko']))
                                                                                            {
                                                                                                echo '<div class="error">'.$_SESSION['err_nazwisko'].'</div>';
                                                                                                unset($_SESSION['err_nazwisko']);
                                                                                            }
                                                                                            ?></td></tr>
			
			<tr><th>Pseudonim artysty:</th><td><input type="text" name="a_pseudonim" value="<?php
                                                                                                if (isset($_SESSION['a_pseudonim']))
                                                                                                {
                                                                                                    echo $_SESSION['a_pseudonim'];
                                                                                                    unset($_SESSION['a_pseudonim']);
                                                                                                }
                                                                                            ?>"></td></tr>
			
			<tr><th>Kraj pochodzenia:</th><td><input type="text" name="a_kraj" value="<?php
                                                                                        if (isset($_SESSION['a_kraj']))
                                                                                        {
                                                                                            echo $_SESSION['a_kraj'];
                                                                                            unset($_SESSION['a_kraj']);
                                                                                        }
                                                                                    ?>">

                                                                                    <?php
                                                                                    if (isset($_SESSION['err_kraj']))
                                                                                    {
                                                                                        echo '<div class="error">'.$_SESSION['err_kraj'].'</div>';
                                                                                        unset($_SESSION['err_kraj']);
                                                                                    }
                                                                                    ?></td></tr>

			<tr><th>Data urodzenia:</th><td><input type="date" name="a_data" value="<?php
                                                                                        if (isset($_SESSION['a_data']))
                                                                                        {
                                                                                            echo $_SESSION['a_data'];
                                                                                            unset($_SESSION['a_data']);
                                                                                        }
                                                                                    ?>">

                                                                                    <?php
                                                                                        if (isset($_SESSION['err_data']))
                                                                                        {
                                                                                            echo '<div class="error">'.$_SESSION['err_data'].'</div>';
                                                                                            unset($_SESSION['err_data']);
                                                                                        }
                                                                                     ?></td></tr>
			
			<tr><th></th><td><input type=submit value="Dodaj artystę"></td></tr>
            
            <?php
		      if(isset($_SESSION['err_artysta']))
		      {
			     echo '<tr><th></th><td><div class="error">'.$_SESSION['err_artysta'].'</div></td></tr>';
			     unset($_SESSION['err_artysta']);
		      }
	       ?>
		</table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>