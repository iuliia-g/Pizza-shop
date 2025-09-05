<?php
include "db.php";
session_start();

// Tarkistetaan, onko käyttaja kirjautunut
// Jos ei ole, lopetetaan skripti ja näytetään viesti
if (!isset($_SESSION['kayttaja'])) {
    die("Kirjaudu sisään nähdäksesi tilaushistorian.");
}

// Tallennetaan kirjautuneen käyttäjän ID muuttujaan
$kayttajaId = $_SESSION['kayttaja'] ['id'];

// Haetaan kaikki käyttäjän tilaukset tietokannasta
// Järjestetään ne luontipäivän mukaan laskevasti (uusin ensin)
$result = $conn->query("SELECT * FROM tilaukset WHERE kayttaja_id = $kayttajaId ORDER BY luotu DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tilaushistoria</title>
</head>
<body>
    <h1>Tilaushistoria</h1>

    <?php 
    if (!$result || $result->num_rows === 0) {
        echo "<p>Sinulla ei ole vielä tilauksia.</p>";
    } else {
        while ($tilaus = $result->fetch_assoc()) {
            echo "<h3>Tilaus #{$tilaus['id']} ({$tilaus['luotu']}) - "
                . number_format($tilaus['yhteensa'], 2) . " €</h3>";

            $rivit = $conn->query("
                SELECT tr.maara, tr.hinta, tu.nimi
                FROM tilausrivit tr
                JOIN tuotteet tu ON tr.tuote_id = tu.id
                WHERE tr.tilaus_id = {$tilaus['id']}
            ");

            echo "<ul>";
            while ($rivi = $rivit->fetch_assoc()) {
                echo "<li>{$rivi['nimi']} ({$rivi['maara']} kpl) - "
                    . number_format($rivi['hinta'], 2) . " €/kpl</li>";
            }
            echo "</ul>";
        }
    }
    ?>  

    <p><a href="index.php">Takaisin kauppaan</a></p>
</body>
</html>
