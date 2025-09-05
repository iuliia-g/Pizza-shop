<?php
include "db.php";
session_start();

// Varmistetaan että admin pääsee sivulle
if (!isset($_SESSION['kayttaja']) || $_SESSION['kayttaja']['rooli'] != 'yllapitaja') {
    die("Et ole yllapitaja, et voi käyttää tätä sivua.");
}

// Lisää tuote
if (isset($_POST['add'])) {
    $nimi = $conn->real_escape_string($_POST['name']);
    $hinta = (float)$_POST['price'];
    $kategoriaId = (int)$_POST['category_id'];
    if (!$conn->query("INSERT INTO tuotteet (nimi, hinta, kategoria_id) VALUES ('$nimi', $hinta, $kategoriaId)")) {
        echo "Virhe lisättäessä tuotetta: " . $conn->error;
    }
}

// Hae kategoriat
$kategoriat = $conn->query("SELECT * FROM kategoriat");

// Hae tilaukset
$tilaukset = $conn->query("SELECT t.id, t.yhteensa, t.luotu, k.kayttajatunnus
    FROM tilaukset t
    JOIN kayttajat k ON t.kayttaja_id = k.id
    ORDER BY t.luotu DESC");

if (!$tilaukset) {
    die("Virhe haettaessa tilauksia: " . $conn->error);
}
?>
