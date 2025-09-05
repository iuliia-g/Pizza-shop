<?php
include "db.php";

// Jos lomake on lähetetty
if (isset($_POST['register'])) {
    $kayttajatunnus = $conn->real_escape_string($_POST['username']);
    $salasana1 = $_POST['password1'];
    $salasana2 = $_POST['password2'];

    // Tarkistetaan että salasanat täsmäävät
    if ($salasana1 !== $salasana2) {
        $error = "Salasanat eivät täsmää!";
    } else {
        // Tarkistetaan onko käyttäjätunnus jo olemassa
        $res = $conn->query("SELECT id FROM kayttajat WHERE kayttajatunnus='$kayttajatunnus'");
        if ($res->num_rows > 0) {
            $error = "Käyttäjätunnus on jo käytössä!";
        } else {
            // Luodaan hash salasanaa varten
            $hash = password_hash($salasana1, PASSWORD_DEFAULT);

            // Lisätään käyttäjä tietokantaan
            if ($conn->query("INSERT INTO kayttajat (kayttajatunnus, salasana, rooli) 
                              VALUES ('$kayttajatunnus', '$hash', 'asiakas')")) {
                $success = "Rekisteröinti onnistui! Voit nyt <a href='login.php'>kirjautua</a>.";
            } else {
                $error = "Virhe tallennuksessa: " . $conn->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Rekisteröidy</title>
    <link rel="stylesheet" href="tyyli.css">
</head>
<body>
    <main>
        <h1>Rekisteröidy</h1>

        <?php if (isset($error)): ?>
            <div id="notif" style="
                background-color: #f8d7da;
                color: #721c24;
                padding: 10px 15px;
                border: 1px solid #f5c6cb;
                border-radius: 5px;
                margin: 15px auto;
                max-width: 400px;
                text-align: center;
                font-weight: bold;
            ">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div id="notif" style="
                background-color: #d4edda;
                color: #155724;
                padding: 10px 15px;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                margin: 15px auto;
                max-width: 400px;
                text-align: center;
                font-weight: bold;
            ">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <section style="max-width: 400px; margin: 0 auto;">
            <form method="post">
                <label for="username">Käyttäjätunnus:</label><br>
                <input type="text" name="username" id="username" required><br><br>

                <label for="password1">Salasana:</label><br>
                <input type="password" name="password1" id="password1" required><br><br>

                <label for="password2">Salasana uudelleen:</label><br>
                <input type="password" name="password2" id="password2" required><br><br>

                <button type="submit" name="register">Rekisteröidy</button>
            </form>

            <p style="text-align:center; margin-top: 20px;">
                <a href="login.php">Kirjaudu sisään</a>
            </p>
        </section>
    </main>
</body>
</html>
