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
            header('location:artysci.php'); exit;
        }
    ?>
	<?php
		if(isset($_GET['id_a']))
        {
            echo '<h2 class="heading-centre">Usuwanie artysty</h2>';
            echo '<div class="search-form"><div class="search-form-child"><b>Jesteś pewny/a?</b></br><a href=artysta_usun.php?id_a='.$_GET['id_a']."&del=1>Tak</a> <a href=artysta_usun.php?id_a=".$_GET['id_a']."&del=2>Nie</a></div></div>"; 

            if(isset($_GET['del']))
            {
                $del=$_GET['del'];
                $id_a=$_GET['id_a'];
                if($del==1)
                {
                    try
                    {
                        $q4=$link->query("SELECT * FROM album_to_artysta WHERE id_artysty='$id_a'");
                        while($row4=$q4->fetch_assoc())
                        {
                            $q1=$link->query("SELECT * FROM album_to_piosenka WHERE id_albumu='{$row4['id_albumu']}'");
                            while($row1 = $q1->fetch_assoc())
                            {
                                $id_p=$row1['id_piosenki'];
                                $q2=$link->query("DELETE FROM piosenka WHERE id_piosenki='$id_p'");
                                if(!$q2) throw new Exception($link->error);
                            }

                            $q3=$link->query("DELETE FROM album WHERE id_albumu='{$row4['id_albumu']}'");
                            if(!$q3) throw new Exception($link->error);
                        }
                        $q3=$link->query("DELETE FROM artysta WHERE id_artysty='$id_a'");
                        if(!$q3) throw new Exception($link->error);;
                        header("location:artysci.php");
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
                }
                if($del==2)
                {
                    header("location:artysta_wyswietl.php?id_a=".$id_a);
                }
            }

            $link->close();
        }
        else
        {
            header("location:artysci.php");
        }
    ?>
    <?php
        require_once("stopka.php");
    ?>
</body>