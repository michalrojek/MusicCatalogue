<?php
    require_once('connect.php');
    //connect with the database
    $link = new mysqli($host,$db_user,$db_password,$db_name);
    //get search term
    $searchTerm = $_GET['term'];
    //get matched data from artysta table
    $query = $link->query("SELECT nazwa_gatunku FROM gatunek
    WHERE nazwa_gatunku COLLATE UTF8_GENERAL_CI LIKE '%".$searchTerm."%'");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['nazwa_gatunku'];
    }
    //return json data
    echo json_encode($data);
?>