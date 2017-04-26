<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.1.1.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php

        require_once('menu.php');

        if($basic_q2['id_typu_uzytkownika']==1)
        {
            header('location:strona_glowna.php'); exit;
        }

    ?>
	<h2 class="heading-centre">Dodawanie zespołu</h2>
	<form method="post" action="zespol_dodawanie.php">
        <?php
            if (isset($_SESSION['udanedodanie']))
            {
                echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie zespołu przebiegło pomyślnie!</span></h3>';
                unset($_SESSION['udanedodanie']);
            }
        ?>
		<table class="adding-form">
			<tr><th>Nazwa zespołu:</th><td><input type="text" name="z_nazwa" value="<?php
															if (isset($_SESSION['z_nazwa']))
															{
																echo $_SESSION['z_nazwa'];
																unset($_SESSION['z_nazwa']);
															}
															?>">
															
															<?php
																if (isset($_SESSION['err_nazwa']))
																{
																	echo '<div class="error">'.$_SESSION['err_nazwa'].'</div>';
																	unset($_SESSION['err_nazwa']);
																}
															?></td><tr>
															
			<tr><th>Rok rozpoczęcia działalności:</th><td><input type="number" name="z_data" min="1" max="2100" value="<?php
															if (isset($_SESSION['z_data']))
															{
																echo $_SESSION['z_data'];
																unset($_SESSION['z_data']);
															}
															?>">
															
															<?php
																if (isset($_SESSION['err_data']))
																{
																	echo '<div class="error">'.$_SESSION['err_data'].'</div>';
																	unset($_SESSION['err_data']);
																}
															?></td></tr>
															
			<tr><th>Członkowie:</th><td>
			<div class="input_fields_wrap">
				<button class="add_field_button">Dodaj więcej członków zespołu</button>
				<div><input type="text" name="z_czlonkowie[]" class="artysta" value="<?php
															if (isset($_SESSION['z_czlonkowie'][0]))
															{
																echo $_SESSION['z_czlonkowie'][0];
															}
															?>">
															<strong>Rok rozpoczęcia współpracy:</strong> <input type="number" name="z_czlonkowie_data[]" min="1" max="2100" value="<?php
															if (isset($_SESSION['z_czlonkowie_data'][0]))
															{
																echo $_SESSION['z_czlonkowie_data'][0];
																unset($_SESSION['z_czlonkowie_data'][0]);
															}
															?>"></div>
															
															<?php
																if (isset($_SESSION['err_czlonkowie'][0]))
																{
																	echo '<div class="error">'.$_SESSION['err_czlonkowie'][0].'</div>';
																	unset($_SESSION['err_czlonkowie'][0]);
																}
															?>
															
															<?php
																if (isset($_SESSION['err_czlonkowie_data'][0]))
																{
																	echo '<div class="error">'.$_SESSION['err_czlonkowie_data'][0].'</div>';
																	unset($_SESSION['err_czlonkowie_data'][0]);
																}
															?>
				<?php
					$x=1;
					if(isset($_SESSION['z_czlonkowie']))
					{
						if(count($_SESSION['z_czlonkowie'])>1)
						{
							$x=count($_SESSION['z_czlonkowie']);
							for($i=1;$i<$x;$i++)
							{
								echo '<div><input type="text" name="z_czlonkowie[]" class="artysta" value="'.$_SESSION['z_czlonkowie'][$i].'"/>
								 <strong>Rok rozpoczęcia współpracy</strong> <input type="number" name="z_czlonkowie_data[]" min="1" max="2100" value="'.$_SESSION['z_czlonkowie_data'][$i].'"/> <a href="#" class="remove_field">Remove</a></div>';
								if (isset($_SESSION['err_czlonkowie'][$i]))
								{
									echo '<div class="error">'.$_SESSION['err_czlonkowie'][$i].'</div>';
									unset($_SESSION['err_czlonkowie'][$i]);
								}
								if (isset($_SESSION['err_czlonkowie_data'][$i]))
								{
									echo '<div class="error">'.$_SESSION['err_czlonkowie_data'][$i].'</div>';
									unset($_SESSION['err_czlonkowie_data'][$i]);
								}
							}
							unset($_SESSION['z_czlonkowie']);
							unset($_SESSION['z_czlonkowie_data']);
						}
						else
						{
							unset($_SESSION['z_czlonkowie']);
							unset($_SESSION['z_czlonkowie_data']);
						}
					}
				?>
                </div></td></tr>
			<tr><th></th><td><input type="submit" value="Dodaj zespół"></td></tr>
		</table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
	<script>
        $(document).ready(function() {
            var max_fields      = 10;
            var wrapper         = $(".input_fields_wrap");
            var add_button      = $(".add_field_button");

            function run() 
            {
                $( ".artysta" ).autocomplete({
                    source: 'search_artysta.php'
                });
            }

            run();

            var x = <?php echo $x; ?>;
            $(add_button).click(function(e){
                e.preventDefault();
                    x++;
                    $(wrapper).append('<div><input type="text" name="z_czlonkowie[]" class="artysta"/> <strong>Rok rozpoczęcia współpracy:</strong> <input type="number" name="z_czlonkowie_data[]" min="1" max="2100"/><a href="#" class="remove_field">Remove</a></div>');
                    $(".artysta").each(function(){
                        run();
                    });
            });

            $(wrapper).on("click",".remove_field", function(e){
                e.preventDefault(); $(this).parent('div').remove(); x--;
            })
        });
	</script>
</body>