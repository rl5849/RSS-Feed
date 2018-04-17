<?php

if (!isset($_POST['username']) or !isset($_POST['fav-title']) or !isset($_POST['fav-url'])){
    echo "ERROR";
}

$username = $_POST['username'];
try{
    $json_data = file_get_contents("data.json");
    $json_data = json_decode($json_data);
}
catch (Exception $e){
    echo $e;
}

foreach ($json_data->USERS as $USER) {
    if ($USER->username == $username) {
        $favs = $json_data->USERS->$username->favorites;

        $new_entry = array('title' => $_POST['fav-title'], 'url' => $_POST['fav-url']);

        array_push($favs, json_encode($new_entry));
        $json_data->USERS->$username->favorites = $favs;

        $fp = fopen('data.json', 'w');
        fwrite($fp, json_encode($json_data));
        fclose($fp);
        echo "{SUCCESS}";

    }
}