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
            header('location:albumy.php'); exit;
        }

    ?>
	<?php
		if(isset($_GET['id_a']))
        {
            echo '<h2 class="heading-centre">Usuwanie albumu</h2>';
            echo '<div class="search-form"><div class="search-form-child"><b>Jesteś pewny/a?</b></br><a href=album_usun.php?id_a='.$_GET['id_a']."&del=1>Tak</a> <a href=album_usun.php?id_a=".$_GET['id_a']."&del=2>Nie</a></div></div>"; 

            try
            {
                if(isset($_GET['del']))
                {
                    $del=$_GET['del'];
                    $id_a=$_GET['id_a'];
                    if($del==1)
                    {
                        $q1=$link->query("SELECT * FROM album_to_piosenka WHERE id_albumu='$id_a'");
                        while($row1 = $q1->fetch_assoc())
                        {
                            $id_p=$row1['id_piosenki'];
                            $q2=$link->query("DELETE FROM piosenka WHERE id_piosenki='$id_p'");
                            if(!$q2) throw new Exception($link->error);
                        }
                        $q3=$link->query("DELETE FROM album WHERE id_albumu='$id_a'");
                        if(!$q3) throw new Exception($link->error);
                        header("location:albumy.php");
                    }
                    if($del==2)
                    {
                        header("location:album_wyswietl.php?id_a=".$id_a);
                    }
                }
            }catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
				echo '<br />Informacja developerska: '.$e;
			}

            $link->close();
        }
        else
        {
            header("location:albumy.php");
        }
    ?>
    <?php
        require_once("stopka.php");
    ?>
</body>