<?php
		session_start();
		
		require_once('connect.php');
		
		$link = mysqli_connect($host,$db_user,$db_password,$db_name);
		
		foreach($_COOKIE as $k=>$v) {$_COOKIE[$k]=mysqli_real_escape_string($link,$v);}
		foreach($_SERVER as $k=>$v) {$_SERVER[$k]=mysqli_real_escape_string($link,$v);}
		
		if(isset($_COOKIE['id']))
        {
            header("location:menu.php");
            exit;
        }
?>