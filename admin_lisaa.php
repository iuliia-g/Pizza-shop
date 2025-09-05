<?php
include 'db.php';
session_start();

// Vain ylläpitäjä voi lisätä tuotteita
//if (!isset($_SESSION['kayttaja']) || $_SESSION['kayttaja']['rooli'] != 'yllapitaja') {
//    die("Vain ylläpitäjä voi käyttää tätä sivua.");
//    exit;
//}

if (isset($_POST['add'])) {
    $nimi = $conn->real_escape_string($_POST['nimi']);
    $hinta = (float)$_POST['hinta'];
    $kategoria_id = (int)$_POST['kategoria_id'];
    $kuva = $conn->real_escape_string($_POST['kuva_url']);
    $conn->query("INSERT INTO tuotteet (nimi, hinta, kategoria_id, kuva_url) VALUES ('$nimi', $hinta, $kategoria_id, '$kuva')");
}

// Hae kategoriat
$kategoriat = $conn->query("SELECT * FROM kategoriat");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisää tuote</title>
</head>
<body>
    <h1>Lisää uusi tuote</h1>
    <form method="post">
        Nimi: <input type="text" name="nimi" required><br><br>
        Hinta: <input type="number" step="0.01" name="hinta" required><br><br>
        Kategoria:
        <select name="kategoria_id">
            <?php while ($k = $kategoriat->fetch_assoc()): ?>
                <option value="<?= $k['id'] ?>"><?= $k['nimi'] ?></option>
            <?php endwhile; ?>
        </select><br><br>
        Kuvan tiedostonimi (esim. margherita.jpg): <input type="text" name="kuva_url"><br><br>
        <button type="submit" name="add">Lisää tuote</button>
    </form>
</body>
</html>
