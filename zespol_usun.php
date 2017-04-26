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
            header('location:zespoly.php'); exit;
        }
    ?>
	<?php
		if(isset($_GET['id_z']))
        {
            echo '<h2 class="heading-centre">Usuwanie zespołu</h2>';
            echo '<div class="search-form"><div class="search-form-child"><b>Jesteś pewny/a?</b></br><a href=zespol_usun.php?id_z='.$_GET['id_z']."&del=1>Tak</a> <a href=zespol_usun.php?id_z=".$_GET['id_z']."&del=2>Nie</a></div></div>"; 

            if(isset($_GET['del']))
            {
                $del=$_GET['del'];
                $id_z=$_GET['id_z'];
                if($del==1)
                {
                    try
                    {
                        $q4=$link->query("SELECT * FROM album_to_zespol WHERE id_zespolu='$id_z'");
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
                        $q3=$link->query("DELETE FROM zespol WHERE id_zespolu='$id_z'");
                        if(!$q3) throw new Exception($link->error);
                        header("location:zespoly.php");
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
                }
                if($del==2)
                {
                    header("location:zespol_wyswietl.php?id_z=".$id_z);
                }
            }

            $link->close();
        }
        else
        {
          header("location:zespoly.php");  
        }
    ?>
    <?php
        require_once("stopka.php");
    ?>
</body>