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
	<h2 class="heading-centre">Dodawanie listy</h2>
    <?php
	
		if(isset($_POST["lista_name"]))
		{
			if(!(strlen($_POST["lista_name"]) > 0 && strlen(trim($_POST["lista_name"])) == 0)&&$_POST["lista_name"]!="")
			{
				$listaName=mysqli_real_escape_string($link,$_POST["lista_name"]);
				$listaName=htmlspecialchars($listaName);
				
				$list_of_lists=$link->query("select * from lista where id_uzytkownika='{$basic_q1['ID_users']}'");
				
				while($row = $list_of_lists->fetch_assoc()) 
				{
					if($row['nazwa_listy']==$listaName)
					{
						$_SESSION['err_lista']="Już utworzyłeś listę o takiej nazwie.";
						$goodToGo=false;
						break;
					}
					else
					{
						$goodToGo=true;
					}
				}
				if(count($row)==0)
				{
					$goodToGo=true;
				}
				if(isset($_POST['opis_value']))
				{
					$opis=mysqli_real_escape_string($link,$_POST["opis_value"]);
					$opis=htmlspecialchars($opis);
				}
				else
				{
					$opis="";
				}
				if(isset($goodToGo)&&$goodToGo)
				{
                    try
                    {
                        $rezultat=$link->query("INSERT INTO lista values(NULL,'{$basic_q1['ID_users']}','$listaName','$opis')");
                        if (!$rezultat) throw new Exception($link->error);
                    }catch(Exception $e)
                    {
                        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o cierpliwość!</span>';
                        echo '<br />Informacja developerska: '.$e;
                    }
				}
			}
			else
			{
				$_SESSION['err_lista']="Niewłaściwa nazwa listy, spróbuj jeszcze raz.";
			}
		}
		$link->close();
	?>
	<form method="post">
        <table class="adding-form">
            <tr><th>Nazwa twojej listy: </th><td><input type="text" name="lista_name"></td></tr>
            <tr><th>Opis listy: </th>
                <td><textarea rows="10" cols="100" name="opis_value" id="opis_text">
                </textarea></td></tr>
            <tr><th></th><td><input type="submit" value="Utwórz listę"></td></tr>
            <?php
                if(isset($_SESSION['err_lista']))
                {
                    echo '<tr><th></th><td class="error">'.$_SESSION['err_lista'].'</td></th>';
                    unset($_SESSION['err_lista']);
                }
            ?>
        </table>
	</form>
    <?php
        require_once("stopka.php");
    ?>
</body>