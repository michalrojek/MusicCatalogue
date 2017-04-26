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
	<h2 class="heading-centre">Listy</h2>
        <div class="search-form">
            <div class="search-from-child">
                <form method="post">
                    <input type="text" name="lista_wyszukaj" placeholder="Wyszukaj listę">
                    <input type="submit" value="Wyszukaj listę">
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
		if(isset($_POST['lista_wyszukaj'])||isset($_GET['lista_wyszukaj']))
		{
			if(isset($_GET['lista_wyszukaj']))
				$artysta_wyszukaj=$_GET['lista_wyszukaj'];

			if(isset($_POST['lista_wyszukaj']))
			$lista_wyszukaj=$_POST['lista_wyszukaj'];
			
			$limit=$page*10;
			
			$q1=$link->query("select * from lista where nazwa_listy COLLATE UTF8_GENERAL_CI LIKE '%{$lista_wyszukaj}%' LIMIT $limit,10");
			
			while($row = $q1->fetch_assoc()) 
			{
				echo '<div class="container"><b>Nazwa listy: </b><a href=wyswietl_liste.php?id_l='.$row["id_listy"].">".$row["nazwa_listy"]."</a></div>";
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from lista where nazwa_listy COLLATE UTF8_GENERAL_CI LIKE '%{$lista_wyszukaj}%'");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="listy.php?page='.$i.'&lista_wyszukaj='.$lista_wyszukaj.'">'.($i+1).'</a> ';
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
			
			$q1=$link->query("select * from lista LIMIT $limit,10");
			
			while($row = $q1->fetch_assoc()) 
			{
				echo '<div class="container"><b>Nazwa listy: </b><a href=wyswietl_liste.php?id_l='.$row["id_listy"].">".$row["nazwa_listy"]."</a></div>";
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from lista");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="listy.php?page='.$i.'">'.($i+1).'</a> ';
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