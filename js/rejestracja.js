$(document).ready(function(){
	$("#register").click(function(){
		var nick = $("#nick").val();
		var email = $("#email").val();
		var haslo1 = $("#haslo1").val();
		var haslo2 = $("#haslo2").val();
		var regulamin = $("#regulamin").prop("checked");
		
		var data = "nick=" + nick +"&email=" + email + "&haslo1=" + haslo1 + "&haslo2=" + haslo2 + "&regulamin=" + regulamin;
		
		$.ajax({
			method: "post",
			url: "rejestracja_potwierdzenie.php?",
			data: data,
			success: function(data)
			{
				if(data=="udanarejestracja")
				{
					$("#error").empty();
					$("#reg_complete").html('Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!</br></br><a href="index.php">Zaloguj się na swoje konto!</a>')
				}
				else
				{
					$("#error").html(data);
				}
			}
		});
		
		
	});
});