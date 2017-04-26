<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Katalog muzyczny - załóż darmowe konto!</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src='https://code.jquery.com/jquery-3.1.1.js' type="text/javascript"></script>
	<script src='js/rejestracja.js'></script>

	<style>
		#error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
		
		#reg_complete
		{
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
	<link rel="stylesheet" href="css/styles.css">
</head>

<body style="padding-top: 0.1rem;">
    <noscript>
      <h3 class="heading-centre">Do pełnej funkcjonalności strony potrzebujesz włączonej obsługi skryptów.
      Tu znajdziesz <a href="http://www.enable-javascript.com/pl/" target="_blank">
      instrukcje, które pozwolą Ci włączyć skrypty w Twojej przeglądarce</a>.</h3>
    </noscript>
	<h2 class="heading-centre">Katalog muzyczny - rejestracja</h2>
	<div class="registration">
		<div class="registration2">
			<div id="error"></div>
			<div id="reg_complete"></div>
			
			Nickname: <br> <input type="text" name="nick" id="nick"/><br>
			
			E-mail: <br> <input type="text" name="email" id="email" /><br>
			
			Twoje hasło: <br> <input type="password" name="haslo1" id="haslo1"/><br>
			
			Powtórz hasło: <br> <input type="password" name="haslo2" id="haslo2"/><br>
			
			<label>
				<input type="checkbox" name="regulamin" id="regulamin"/> Akceptuję regulamin
			</label>
			
			<br>
			
			<button id="register">Zarejestruj sie</button><br>
			<a href="index.php">Wróć do strony głównej</a>
		</div>
	</div>
    <?php
        require_once("stopka.php");
    ?>
</body>
</html>