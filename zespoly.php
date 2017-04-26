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

	<h2 class="heading-centre">Zespoły</h2>
        <div class="search-form">
            <div class="search-from-child">
                <form method="post">
                    <input type="text" name="zespol_wyszukaj" placeholder="Wyszukaj zespół">
                    <input type="submit" value="Wyszukaj zespół">
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
		if(isset($_POST['zespol_wyszukaj'])||isset($_GET['zespol_wyszukaj']))
		{
			if(isset($_GET['zespol_wyszukaj']))
				$zespol_wyszukaj=$_GET['zespol_wyszukaj'];

			if(isset($_POST['zespol_wyszukaj']))
                $zespol_wyszukaj=$_POST['zespol_wyszukaj'];
			
			$limit=$page*10;
			
			$q1=$link->query("select * from zespol where nazwa_zespolu COLLATE UTF8_GENERAL_CI LIKE '%{$zespol_wyszukaj}%' LIMIT $limit,10");
			
			while($row = $q1->fetch_assoc()) 
			{
				echo '<div class="container"><b>Zespół: </b><a href=zespol_wyswietl.php?id_z='.$row["id_zespolu"].">".$row["nazwa_zespolu"]."</a></div>";
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from zespol where nazwa_zespolu COLLATE UTF8_GENERAL_CI LIKE '%{$zespol_wyszukaj}%'");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="zespoly.php?page='.$i.'&zespol_wyszukaj='.$zespol_wyszukaj.'">'.($i+1).'</a> ';
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
			
			$q1=$link->query("select * from zespol LIMIT $limit,10");
			
			while($row = $q1->fetch_assoc()) 
			{
				echo '<div class="container"><b>Zespół: </b><a href=zespol_wyswietl.php?id_z='.$row["id_zespolu"].">".$row["nazwa_zespolu"]."</a></div>";
			}
			
			echo '</br></br>';
			$q2=$link->query("select * from zespol");
			$row2=$q2->fetch_assoc();
			$i=0;
			$count=mysqli_num_rows($q2)/10;
            echo '<div class="page-container"><b>Strony: </b>';
			for($i;$i<$count;$i++)
			{
                if($i!=$page)
                {
				    echo '<a href="zespoly.php?page='.$i.'">'.($i+1).'</a> ';
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