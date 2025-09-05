<?php
$host = "localhost";
$user = "215576";              // käyttäjätunnus
$password = "zcgXzIib92mOMQbs"; // salasana
$database = "215576";          // tietokannan nimi

// luodaan yhteys tietokantaan
$conn = new mysqli($host, $user, $password, $database);

// tarkistetaan yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}
?>