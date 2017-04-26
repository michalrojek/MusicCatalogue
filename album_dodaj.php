<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Katalog muzyczny</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="css/styles.css">
	<script src="https://code.jquery.com/jquery-3.1.1.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" type="text/javascript"></script>
</head>
<body>
    <?php

        require_once('menu.php');

        if($basic_q2['id_typu_uzytkownika']==1)
        {
            header('location:strona_glowna.php'); exit;
        }

    ?>
	<h2 class="heading-centre">Dodawanie albumu</h2>
	<form method="post" action="album_dodawanie.php">
        <?php
            if (isset($_SESSION['udanedodanie']))
            {
                echo '<h3 class="heading-centre"><span style="color:green;">Dodawanie albumu przebiegło pomyślnie!</span></h3>';
                unset($_SESSION['udanedodanie']);
            }
        ?>
		<table class="adding-form">
			<tr><th>Tytuł albumu:</th><td><input type="text" name="a_tytul" value="<?php
																						if (isset($_SESSION['a_tytul']))
																						{
																							echo $_SESSION['a_tytul'];
																							unset($_SESSION['a_tytul']);
																						}
																					?>">
			
																					<?php
																						if (isset($_SESSION['err_tytul']))
																						{
																							echo '<div class="error">'.$_SESSION['err_tytul'].'</div>';
																							unset($_SESSION['err_tytul']);
																						}
																					?></td></tr>
							
			<tr><th>Autor:</th><td><input type="text" name="a_autor" class="autor" value="<?php
																								if (isset($_SESSION['a_autor']))
																								{
																									echo $_SESSION['a_autor'];
																									unset($_SESSION['a_autor']);
																								}
																							?>">
			
																							<?php
																								if (isset($_SESSION['err_autor']))
																								{
																									echo '<div class="error">'.$_SESSION['err_autor'].'</div>';
																									unset($_SESSION['err_autor']);
																								}
																							?></td></tr>
							
			<tr><th>Gatunek:</th><td>
			<div class="input_fields_wrap1">
				<button class="add_field_button1">Dodaj więcej gatunków</button>
				<div><input type="text" name="mygat[]" class="gatunek" value="<?php
																	if (isset($_SESSION['mygat'][0]))
																	{
																		echo $_SESSION['mygat'][0];
																	}
																?>"></div>
																
																<?php
																	if (isset($_SESSION['err_gatunek'][0]))
																	{
																		echo '<div class="error">'.$_SESSION['err_gatunek'][0].'</div>';
																		unset($_SESSION['err_gatunek'][0]);
																	}
																?>
															
														<?php
															$y=1;
															if(isset($_SESSION['mygat']))
															{
																if(count($_SESSION['mygat'])>1)
																{
																	$y=count($_SESSION['mygat']);
																	for($i=1;$i<$y;$i++)
																	{
																		echo '<div><input type="text" name="mygat[]" class="gatunek: value="'.$_SESSION['mygat'][$i].
																			'"/><a href="#" class="remove_field_g">Remove</a></div>';
																		if (isset($_SESSION['err_gatunek'][$i]))
																		{
																			echo '<div class="error">'.$_SESSION['err_gatunek'][$i].'</div>';
																			unset($_SESSION['err_gatunek'][$i]);
																		}
																	}
																	unset($_SESSION['mygat']);
																}
																else
																{
																	unset($_SESSION['mygat']);
																}
															}
														?>
            </div></td></tr>
            
			<tr><th>Rok wydania:</th><td><input type="date" name="a_data" value="<?php
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
            
			<tr><th>Typ albumu:</th><td><div id="a_typ">
							<?php
								if(isset($_SESSION['a_type']))
								{
									$a_type=$_SESSION['a_type'];
									unset($_SESSION['a_type']);
								}
								else
								{
									$a_type="";
								}
									
								$q1=$link->query("select * from typ_albumu");
								while($row1=$q1->fetch_assoc())
								{
									if($row1['nazwa_typu_albumu']==$a_type)
									{
										echo '<input type="radio" name="a_type" value="'.$row1['nazwa_typu_albumu'].'" id="a_type" checked> '.$row1['nazwa_typu_albumu'].'</br>';
									}
									else
									{
										echo '<input type="radio" name="a_type" value="'.$row1['nazwa_typu_albumu'].'" id="a_type"> '.$row1['nazwa_typu_albumu'].'</br>';
									}
								}
                
                                if (isset($_SESSION['err_typ']))
                                {
                                     echo '<div class="error">'.$_SESSION['err_typ'].'</div>';
                                     unset($_SESSION['err_typ']);
                                }
							?>
						</div></td></tr>

			<tr><th>Wydania:</th><td><div id="a_wydanie">
							<?php							
								$q1=$link->query("select * from wydanie");
								if(isset($_SESSION['a_wyd']))
								{
									while($row1=$q1->fetch_assoc())
									{
										$flag=false;
											foreach($_SESSION['a_wyd'] as $item)
											{
												if($row1['nazwa_wydania']==$item)
												{
													$flag=true;
												}
											}
											if($flag)
											{
												echo '<input type="checkbox" name="a_wyd[]" value="'.$row1['nazwa_wydania'].'" id="a_wyd" checked> '.$row1['nazwa_wydania'].'</br>';
											}
											else
											{
												echo '<input type="checkbox" name="a_wyd[]" value="'.$row1['nazwa_wydania'].'" id="a_wyd"> '.$row1['nazwa_wydania'].'</br>';
											}
									}
									unset($_SESSION['a_wyd']);
								}
								else
								{
									while($row1=$q1->fetch_assoc())
									{
										echo '<input type="checkbox" name="a_wyd[]" value="'.$row1['nazwa_wydania'].'" id="a_wyd"> '.$row1['nazwa_wydania'].'</br>';
									}
								}
                
                                if (isset($_SESSION['err_wydanie']))
                                {
                                    echo '<div class="error">'.$_SESSION['err_wydanie'].'</div>';
                                    unset($_SESSION['err_wydanie']);
                                }
							?>
						</div></td></tr>
            
			<tr><th>Piosenki:</th><td>
			<div class="input_fields_wrap2">
				<button class="add_field_button2">Dodaj więcej piosenek</button>
				<div><input type="text" name="piosenki[]" value="<?php
                                                                    if (isset($_SESSION['piosenki'][0]))
                                                                    {
                                                                        echo $_SESSION['piosenki'][0];
                                                                    }
                                                                ?>">
                    
				    <input type="time" name="czas[]" step="1" value="<?php
                                                                        if (isset($_SESSION['czas'][0]))
                                                                        {
                                                                            echo $_SESSION['czas'][0];
                                                                        }
                                                                    ?>"/></div>
                
                                                                    <?php
                                                                        if (isset($_SESSION['err_piosenki'][0]))
                                                                        {
                                                                            echo '<div class="error">'.$_SESSION['err_piosenki'][0].'</div>';
                                                                            unset($_SESSION['err_piosenki'][0]);
                                                                        }
                                                                    ?>
                
                                                                    <?php
                                                                        if (isset($_SESSION['err_piosenki_czas'][0]))
                                                                        {
                                                                            echo '<div class="error">'.$_SESSION['err_piosenki_czas'][0].'</div>';
                                                                            unset($_SESSION['err_piosenki_czas'][0]);
                                                                        }
                                                                    ?>
                
						<?php
                            $x=1;
                            if(isset($_SESSION['piosenki']))
                            {
                                if(count($_SESSION['piosenki'])>1)
                                {
                                    $x=count($_SESSION['piosenki']);
                                    for($i=1;$i<$x;$i++)
                                    {
                                        echo '<div><input type="text" name="piosenki[]" value="'.$_SESSION['piosenki'][$i].'"/>
                                         <input type="time" name="czas[]" step="1" value="'.$_SESSION['czas'][$i].'"/> <a href="#" class="remove_field">Remove</a></div>';
                                        if (isset($_SESSION['err_piosenki'][$i]))
                                        {
                                            echo '<div class="error">'.$_SESSION['err_piosenki'][$i].'</div>';
                                            unset($_SESSION['err_piosenki'][$i]);
                                        }
                                        if (isset($_SESSION['err_piosenki_czas'][$i]))
                                        {
                                            echo '<div class="error">'.$_SESSION['err_piosenki_czas'][$i].'</div>';
                                            unset($_SESSION['err_piosenki_czas'][$i]);
                                        }
                                    }
                                    unset($_SESSION['piosenki']);
                                    unset($_SESSION['czas']);
                                }
                                else
                                {
                                    unset($_SESSION['piosenki']);
                                    unset($_SESSION['czas']);
                                }
                            }
						?>
                
						<?php
                            if (isset($_SESSION['err_typ_czas']))
                            {
                                echo '<div class="error">'.$_SESSION['err_typ_czas'].'</div>';
                                unset($_SESSION['err_typ_czas']);
                            }
						?>
			</div></td></tr>
						
			<tr><th></th><td><input type="submit" value="Dodaj album"></td></tr>
		</table>
    </form>
    
    <?php
        require_once("stopka.php");
    ?>
    
	<script>
        $(document).ready(function() {
            var max_fields;
            var max_fields_g=5;

            function run() {
                $( ".autor" ).autocomplete({
                    source: 'search_artysta_zespol.php'
                });
                
                $( ".gatunek" ).autocomplete({
                    source: 'search_gatunek.php'
                });
            }

            run();

            $('#a_typ input').on('change', function() {
                if($('input[name=a_type]:checked', '#a_typ').val()=='single')
                {
                    max_fields=2;
                    if(x>2)
                    {
                        while(x!=2)
                        {
                            $('.input_fields_wrap2 div:last-child').remove(); 
                            x--;
                        }
                    }		
                }
                else if($('input[name=a_type]:checked', '#a_typ').val()=='ep')
                {
                    max_fields=5;
                    if(x>5)
                    {
                        while(x!=5)
                        {
                            $('.input_fields_wrap2 div:last-child').remove(); 
                            x--;
                        }
                    }	
                }
                else
                {
                    max_fields=200;
                }
            });
            
            //gatunki
            var wrapper_g         = $(".input_fields_wrap1");
            var add_button_g      = $(".add_field_button1");

            var y = <?php echo $y; ?>;
            $(add_button_g).click(function(e){
                e.preventDefault();
                    y++;
                    $(wrapper_g).append('<div><input type="text" name="mygat[]" class="gatunek"/><a href="#" class="remove_field_g">Remove</a></div>');
                    $(".gatunek").each(function(){
                        run();
                    });
            });

            $(wrapper_g).on("click",".remove_field_g", function(e){
                e.preventDefault(); $(this).parent('div').remove(); y--;
            })
            
            //piosenki
            var wrapper         = $(".input_fields_wrap2");
            var add_button      = $(".add_field_button2");

            var x = <?php echo $x; ?>;
            $(add_button).click(function(e){
                e.preventDefault();
                if(x < max_fields)
                {
                    x++;
                    $(wrapper).append('<div><input type="text" name="piosenki[]"/> <input type="time" name="czas[]" step="1"/><a href="#" class="remove_field">Remove</a></div>');
                }
            });

            $(wrapper).on("click",".remove_field", function(e){
                e.preventDefault(); $(this).parent('div').remove(); x--;
            })
        });
	</script>
</body>