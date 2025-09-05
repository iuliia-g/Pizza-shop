<?php
// Otetaan tietokantayhteys käyttöön
include "db.php";

// Käynnistetään sessio, jotta voimme seurata kirjautuneita käyttäjiä ja ostoksia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Haetaan tuotteet tietokannasta
$result = $conn->query("SELECT t.id, t.nimi, t.hinta, t.kuva_url, k.nimi AS kategoria
    FROM tuotteet t
    JOIN kategoriat k ON k.id = t.kategoria_id
    ORDER BY k.nimi DESC");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza-kauppa</title>
    <link rel="stylesheet" href="tyyli.css">
</head>
<body>
    <main class="container">
        <h1>🍕Tervetuloa Pizza-kauppaan!🍕</h1>

        <?php
        $currentCat = "";

        while ($row = $result->fetch_assoc()) {
            if ($currentCat != $row['kategoria']) {
                $currentCat = $row['kategoria'];
                echo "<h2>{$currentCat}</h2>";
            }

            echo "<form method='post' action='ostoskori.php' class='product-line'>
                <strong>" . htmlspecialchars($row['nimi']) . "</strong> – " . number_format($row['hinta'], 2) . " €
                <input type='hidden' name='product_id' value='{$row['id']}'>
                <input type='number' name='quantity' value='1' min='1'>
                <button type='submit' name='add'>Lisää koriin</button>
            </form>";
        }
        ?>

        <section class="links">
            <p><a href="ostoskori.php" class="back-link">🛒 Ostoskori</a></p>
            <p><a href="tilaus.php" class="back-link">📦 Oma tilaushistoria</a></p>

            <?php if (isset($_SESSION['kayttaja'])): ?>
                <p>Kirjautunut: <?= htmlspecialchars($_SESSION['kayttaja']['kayttajatunnus']) ?>
                (<a href="logout.php" class="back-link">Kirjaudu ulos</a>)</p>
            <?php else: ?>
                <p><a href="login.php" class="back-link">Kirjaudu</a> | <a href="rekisterointi.php">Rekisteröidy</a></p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
