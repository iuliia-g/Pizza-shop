<!-- Selitykset tärkeimmille kohdille:

include "db.php"; - yhdistää tietokannan ja mahdollistaa sen käytön.
include "db.php";

session_start(); - käynnistää kirjautumisen ja ostoskorin sessionin.

Jos ostokset & ON t.kanta, session_id = k.id - haetaan ostotietojen pvm.

$user = $row['kayttaja']; - tarkistetaan onko käyttäjä kirjautunut,
jotta voidaan näyttää hänen nimensä ja uloskirjautumislinkki.
-->

<?php
// Otetaan tietokantayhteys käyttöön
include "db.php";

// Käynnistetään sessio, jotta voimme seurata kirjautuneita käyttäjiä ja ostoksia
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Muista käynnistää sessio
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza-kauppa</title>
</head>
<body>
    <h1>🍕Tervetuloa Pizza-kauppaan!🍕</h1>

    <?php
    // Haetaan kaikki tuotteet tietokannasta, yhdistettynä niiden kategorioihin
    $result = $conn->query("SELECT t.id, t.nimi, t.hinta, t.kuva_url, k.nimi AS kategoria
        FROM tuotteet t
        JOIN kategoriat k ON k.id = t.kategoria_id
        ORDER BY k.nimi DESC");

    // Muuttuja nykyisen kategorian seuraamiseen
    $currentCat = "";

    // Käydään kaikki tuotteet läpi
    while ($row = $result->fetch_assoc()) {
        if ($currentCat != $row['kategoria']) {
            $currentCat = $row['kategoria'];
            echo "<h2>{$currentCat}</h2>";
        }

        // Luodaan lomake jokaiselle tuotteelle, jotta sen voi lisätä ostoskoriin
        // tai voi lisätä taulukkorakenteeseen jossa vaikka 4 pizzaa rivissä tai muuta?
        echo "<form method='post' action='ostoskori.php'>
            {$row['nimi']} - {$row['hinta']} €
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='number' name='quantity' value='1' min='1'>
            <button type='submit' name='add'>Lisaa koriin</button>
        </form>";
    }
    ?>

    <!-- Linkki ostoskoriin -->
    <p><a href="ostoskori.php">Ostoskori</a></p>

    <!-- Linkki käyttäjän tilaushistoriaan -->
    <p><a href="tilaus.php">Oma tilaushistoria</a></p>

    <?php
    // Näytetään kirjautumistiedot, jos käyttäjä on kirjautunut
    if (isset($_SESSION['kayttaja']) ): ?>
        <p>Kirjautunut: <?= $_SESSION['kayttaja'] ['kayttajatunnus'] ?>
        (<a href="logout.php">Kirjaudu ulos</a>)</p>
    <?php else: ?>
        <p><a href="login.php">Kirjaudu</a> | <a href="rekisterointi.php">Rekisteröidy</a></p>
    <?php endif; ?>
</body>
</html>
