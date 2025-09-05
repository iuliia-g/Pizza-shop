<?php
include "db.php";
// Muista käynnistää sessio
//session_start(); // Muista käynnistää sessio

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Jos lomake on lähetetty
if (isset($_POST['login'])) {
    $kayttajatunnus = $conn->real_escape_string($_POST['username']);
    $salasana = $_POST['password'];

    //Haetaan käyttäjä tietokannasta
    $res = $conn->query("SELECT * FROM kayttajat WHERE kayttajatunnus='$kayttajatunnus'");
    if ($res->num_rows == 1) {
        $kayttaja = $res->fetch_assoc();

        //Tarkistetaan salasana
        if (password_verify($salasana, $kayttaja['salasana'])) {
            $_SESSION['kayttaja'] = $kayttaja; //Tallennetaan sessioon
            header("Location: index.php");
            exit;
        } else {
            $error = "Väära salasana!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Kirjaudu</title>
    <link rel="stylesheet" href="tyyli.css">
</head>
<body>
    <main>
        <h1>Kirjaudu sisään</h1>

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

        <section style="max-width: 400px; margin: 0 auto;">
            <form method="post">
                <label for="username">Käyttäjätunnus:</label><br>
                <input type="text" name="username" id="username" required><br><br>

                <label for="password">Salasana:</label><br>
                <input type="password" name="password" id="password" required><br><br>

                <button type="submit" name="login">Kirjaudu</button>
            </form>

            <p style="text-align:center; margin-top: 20px;">
                <a href="rekisterointi.php">Rekisteröidy</a>
            </p>
        </section>
    </main>
</body>
</html>