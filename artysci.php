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
	<h2 class="heading-centre">Artyści</h2>
        <div class="search-form">
            <div class="search-from-child">
                <form method="post">
                    <input type="text" name="artysta_wyszukaj" placeholder="Wyszukaj artystę">
                    <input type="submit" value="Wyszukaj artystę">
                </form>
            </div>
        </div>
	<?php
		if(isset($_GET['page']))
		{
			$page=$_GET['page'];
		}
		else
		{
			$page=0;
		}
		if(isset($_POST['artysta_wyszukaj'])||isset($_GET['artysta_wyszukaj']))
		{
			if(isset($_GET['artysta_wyszukaj']))
				$artysta_wyszukaj=$_GET['artysta_wyszukaj'];

			if(isset($_POST['artysta_wyszukaj']))
			$artysta_wyszukaj=$_POST['artysta_wyszukaj'];
			
			$limit=$page*10;
			
			$q1=$link->query("select * from artysta where imie_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%' OR nazwisko_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%'
						OR pseudonim_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%' LIMIT $limit,10");
			
			while($row1 = $q1->fetch_assoc()) 
			{
				if($row1["pseudonim_artysty"]!="")
				{
					echo '<div class="container"><b>Artysta: </b><a href=artysta_wyswietl.php?id_a='.$row1["id_artysty"].'>'.$row1["imie_artysty"].' "'.$row1["pseudonim_artysty"].'" '.$row1["nazwisko_artysty"].'</a></div>';
				}
				else
				{
					echo '<div class="container"><b>Artysta: </b><a href=artysta_wyswietl.php?id_a='.$row1["id_artysty"].'>'.$row1["imie_artysty"].' '.$row1["nazwisko_artysty"].'</a></div>';
				}
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from artysta where imie_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%' OR nazwisko_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%'
						OR pseudonim_artysty COLLATE UTF8_GENERAL_CI LIKE '%{$artysta_wyszukaj}%'");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="artysci.php?page='.$i.'&artysta_wyszukaj='.$artysta_wyszukaj.'">'.($i+1).'</a> ';
                }
                else
                {
                    echo ' '.($i+1).' ';
                }
			}
            echo '</div>';
		}
		else
		{
			$limit=$page*10;
			
			$q1=$link->query("select * from artysta LIMIT $limit,10");
			
			while($row1 = $q1->fetch_assoc()) 
			{
				if($row1["pseudonim_artysty"]!="")
				{
					echo '<div class="container"><b>Artysta: </b><a href=artysta_wyswietl.php?id_a='.$row1["id_artysty"].'>'.$row1["imie_artysty"].' "'.$row1["pseudonim_artysty"].'" '.$row1["nazwisko_artysty"].'</a></div>';
				}
				else
				{
					echo '<div class="container"><b>Artysta: </b><a href=artysta_wyswietl.php?id_a='.$row1["id_artysty"].'>'.$row1["imie_artysty"].' '.$row1["nazwisko_artysty"].'</a></div>';
				}
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from artysta");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="artysci.php?page='.$i.'">'.($i+1).'</a> ';
                }
                else
                {
                    echo ' '.($i+1).' ';
                }
			}
            echo '</div>';
		}
		$link->close();
	?>
    <?php
        require_once("stopka.php");
    ?>
</body>