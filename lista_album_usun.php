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
    ?>
	<?php
		if(isset($_GET['id_l'])&&isset($_GET['id_a']))
        {
            echo '<h2 class="heading-centre">Usuwanie albumu z listy</h2>';
            echo '<div class="search-form"><div class="search-form-child"><b>Jesteś pewny/a?</b></br><a href=lista_album_usun.php?id_l='.$_GET['id_l']."&id_a=".$_GET['id_a']."&del=1>Tak</a> <a href=lista_album_usun.php?id_l=".$_GET['id_l']."&id_a=".$_GET['id_a']."&del=2>Nie</a></div></div>"; 

            if(isset($_GET['del']))
            {
                $del=$_GET['del'];
                $id_a=$_GET['id_a'];
                $id_l=$_GET['id_l'];
                if($del==1)
                {
                    try
                    {
                        $rezultat=$link->query("delete from album_to_lista where id_albumu='$id_a' and id_listy='$id_l'");
                        if(!$rezultat) throw new Exception($link->error);
                        header("location:edytuj_liste.php?id_l=".$id_l);
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
                }
                if($del==2)
                {
                    header("location:edytuj_liste.php?id_l=".$id_l);
                }
            }

            $link->close();
        }
        else
        {
            header("location:moje_listy.php");
        }
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>