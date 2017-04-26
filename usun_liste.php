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
        if(isset($_GET['id_l']))
        {
            $q2=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM LISTA WHERE id_listy='{$_GET['id_l']}'"));

            echo '<h2 class="heading-centre">Usuwanie listy '.$q2['nazwa_listy'].'</h2>';

            echo '<div class="search-form"><div class="search-form-child"><b>Jesteś pewny/a?</b></br><a href=usun_liste.php?id_l='.$_GET['id_l']."&del=1>Tak</a> <a href=usun_liste.php?id_l=".$_GET['id_l']."&del=2>Nie</a></div></div>"; 

            if(isset($_GET['del']))
            {
                $del=$_GET['del'];
                $id_l=$_GET['id_l'];
                if($del==1)
                {
                    try
                    {
                        $q1=$link->query("DELETE FROM lista WHERE id_listy='$id_l'");
                        if(!$q1) throw new Exception($link->error);
                        header("location:moje_listy.php");
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
                }
                if($del==2)
                {
                    header("location:moje_listy.php");
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