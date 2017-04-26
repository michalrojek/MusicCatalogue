<?php
    require_once('connect.php');
    //connect with the database
    $link = new mysqli($host,$db_user,$db_password,$db_name);
    //get search term
    $searchTerm = $_GET['term'];
    //get matched data from artysta,zespol tables
    $query = $link->query("SELECT concat(artysta.imie_artysty,' ',artysta.nazwisko_artysty) as autor FROM artysta 
    WHERE concat(imie_artysty,' ',nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '%".$searchTerm."%' AND artysta.pseudonim_artysty=''
    UNION
    SELECT concat(artysta.imie_artysty,' ',artysta.pseudonim_artysty,' ',artysta.nazwisko_artysty) as autor FROM artysta 
    WHERE concat(artysta.imie_artysty,' ',artysta.pseudonim_artysty,' ',artysta.nazwisko_artysty) COLLATE UTF8_GENERAL_CI LIKE '%".$searchTerm."%' AND artysta.pseudonim_artysty not like ''
    UNION
    select zespol.nazwa_zespolu as autor from zespol where zespol.nazwa_zespolu COLLATE UTF8_GENERAL_CI LIKE '%".$searchTerm."%' LIMIT 0,10");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['autor'];
    }
    //return json data
    echo json_encode($data);
?>