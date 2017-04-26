<?php
	require_once('zalogowano.php');
?>
<nav>
        <div>
            <ul>
                <li><a href="strona_glowna.php">Strona główna</a></li>
                <li><a href="albumy.php">Albumy</a></li>
                <li><a href="artysci.php">Artyści</a></li>
                <li><a href="zespoly.php">Zespoły</a></li>
                <li><a href="listy.php">Listy</a></li>
                <li><a href="moje_listy.php">Moje listy</a></li>
                <?php
                    if($basic_q2['id_typu_uzytkownika']==2)
                    {
                        echo '<li class="dropdown"><span>Panel administracyjny</span>
                                <ul class="dropdown-content">
                                <li><a href="album_dodaj.php" style="margin-left:5px;">Dodaj album</a></li>
                                <li><a href="artysta_dodaj.php">Dodaj artystę</a></li>
                                <li><a href="zespol_dodaj.php">Dodaj zespół</a></li>
                                <li><a href="gatunek_dodaj.php">Dodaj gatunek</a></li>
                                <li><a href="wydanie_dodaj.php">Dodaj wydanie</a></li>
                                <li><a href="typ_dodaj.php">Dodaj typ albumu</a></li>
                                </ul>
                        </li> ';
                    }
                ?>
                
                <li><a href="?logout"><?php echo $basic_q2['login']; ?> (wyloguj)</a></li>
            </ul>

        </div>
</nav>
<br><br><br>
<script>
    $(document).ready(function () {
        $(".dropdown").hover(function () {
            $('ul.dropdown-content').slideDown('medium');
        }, 
        function () {
            $('ul.dropdown-content').slideUp('medium');
        }
                           );

    });
</script>
<noscript>
  <h3 class="heading-centre">Do pełnej funkcjonalności strony potrzebujesz włączonej obsługi skryptów.
  Tu znajdziesz <a href="http://www.enable-javascript.com/pl/" target="_blank">
  instrukcje, które pozwolą Ci włączyć skrypty w Twojej przeglądarce</a>.</h3>
</noscript>