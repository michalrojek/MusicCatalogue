<?php
    session_start();
		
    require_once('connect.php');
		
    $link = mysqli_connect($host,$db_user,$db_password,$db_name);

    if($_SERVER['REQUEST_URI']=="/php1/menu.php")
    {
        header("location:strona_glowna.php");
    }
		
    foreach($_COOKIE as $k=>$v) {$_COOKIE[$k]=mysqli_real_escape_string($link,$v);}
    foreach($_SERVER as $k=>$v) {$_SERVER[$k]=mysqli_real_escape_string($link,$v);}
		
    if(!isset($_COOKIE['id']))
    {
        header("location:index.php");
        exit;
    }
		
    $basic_q1=mysqli_fetch_assoc(mysqli_query($link,"select ID_users from sesja where id='$_COOKIE[id]' and web='$_SERVER[HTTP_USER_AGENT]' and 
    ip='$_SERVER[REMOTE_ADDR]';"));
    $basic_q2=mysqli_fetch_assoc(mysqli_query($link,"select login,email,id_typu_uzytkownika from uzytkownik where id_uzytkownika='{$basic_q1['ID_users']}';"));
		
    if(!empty($basic_q1['ID_users']))
    {
        //echo "Zalogowany uzytkownik o loginie: ".$basic_q2['login']."<br>";
    }
    else
    {
        header("location:index.php");
        exit;
    }
	
	if(isset($_GET['logout']))
    {
        $basic_q1=mysqli_query($link,"delete from sesja where id='$_COOKIE[id]' and web ='$_SERVER[HTTP_USER_AGENT]';");
        setcookie("id",0,time()-1);
        unset($_COOKIE['id']);
        header("location:index.php");
    }
?>