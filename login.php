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
<html>
<head>
    <meta charset="UTF-8">
    <title>Kirjaudu</title>
</head>
<body>
    <h1>Kirjaudu</h1>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        Käyttäjätunnus: <input type="text" name="username" required><br>
        Salasana: <input type="password" name="password" required><br>
        <button type="submit" name="login">Kirjaudu</button>
    </form>

    <p><a href="rekisterointi.php">Rekisteröidy</a></p>
</body>
</html>
