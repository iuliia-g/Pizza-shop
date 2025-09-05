<?php
include "db.php";
//session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Muista käynnistää sessio
}

if (!isset($_SESSION['kayttaja'])) {
    die("Kirjaudu sisään tehdäksesi tilauksen. <a href='login.php'>Kirjaudu</a>");
}

if (empty($_SESSION['cart'])) {
    die("Ostoskori on tyhjä. <a href='index.php'>Takaisin</a>");
}

$ids = implode(",", array_keys($_SESSION['cart']));
$result = $conn->query("SELECT * FROM tuotteet WHERE id IN ($ids)");

$total = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']];
    $line = $qty * $row['hinta'];
    $total += $line;
    $items[] = [
        "id" => $row['id'],
        "hinta" => $row['hinta'],
        "maara" => $qty
    ];
}

// Lisää tilaus
$kayttajaID = $_SESSION['kayttaja']['id'];
$conn->query("INSERT INTO tilaukset (kayttaja_id, yhteensa) VALUES ($kayttajaID, $total)");
$tilausID = $conn->insert_id;

// Lisää tilausrivit
foreach ($items as $it) {
    $conn->query("INSERT INTO tilausrivit (tilaus_id, tuote_id, maara, hinta)
    VALUES ($tilausID, {$it['id']}, {$it['maara']}, {$it['hinta']})");
}

// Tyhjennä ostoskori
$_SESSION['cart'] = [];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tilaus vahvistettu</title>
</head>
<body>
    <h1>Tilaus valmis!</h1>
    <p>Tilausnumero: <?= $tilausID ?></p>
    <p><a href="index.php">Takaisin kauppaan</a></p>
</body>
</html>
