<?php
include "db.php";
session_start();

// Tarkistetaan, onko käyttäjä kirjautunut
if (!isset($_SESSION['kayttaja'])) {
    die("<p>Kirjaudu sisään nähdäksesi tilaushistorian.</p><p><a href='kirjaudu.php'>Kirjaudu sisään</a></p>");
}

$kayttajaId = $_SESSION['kayttaja']['id'];

// Valmistellaan tilauskysely turvallisesti
$stmt = $conn->prepare("SELECT * FROM tilaukset WHERE kayttaja_id = ? ORDER BY luotu DESC");
$stmt->bind_param("i", $kayttajaId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Tilaushistoria</title>
    <link rel="stylesheet" href="tyyli.css">
</head>
<body>
    <main class="order-history-container">
        <h1>📦 Tilaushistoria</h1>

        <?php 
        if ($result->num_rows === 0) {
            echo "<p>Sinulla ei ole vielä tilauksia.</p>";
        } else {
            while ($tilaus = $result->fetch_assoc()) {
                $tilausId = $tilaus['id'];
                $luotu = date("d.m.Y H:i", strtotime($tilaus['luotu']));
                $yhteensa = number_format($tilaus['yhteensa'], 2);

                echo "<section class='order-block'>";
                echo "<h3>Tilaus #$tilausId <span class='order-date'>($luotu)</span> – <strong>$yhteensa €</strong></h3>";

                // Haetaan tilausrivit
                $riviStmt = $conn->prepare("
                    SELECT tr.maara, tr.hinta, tu.nimi
                    FROM tilausrivit tr
                    JOIN tuotteet tu ON tr.tuote_id = tu.id
                    WHERE tr.tilaus_id = ?
                ");
                $riviStmt->bind_param("i", $tilausId);
                $riviStmt->execute();
                $rivit = $riviStmt->get_result();

                echo "<ul class='order-items'>";
                while ($rivi = $rivit->fetch_assoc()) {
                    $nimi = htmlspecialchars($rivi['nimi']);
                    $maara = (int)$rivi['maara'];
                    $hinta = number_format($rivi['hinta'], 2);
                    echo "<li>$nimi ($maara kpl) – $hinta €/kpl</li>";
                }
                echo "</ul>";
                echo "</section>";
            }
        }
        ?>  

        <p><a href="index.php">⬅️ Takaisin kauppaan</a></p>
    </main>
</body>
</html>
