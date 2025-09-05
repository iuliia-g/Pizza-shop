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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröidy</title>
</head>
<body>
    <h1>Rekisteröidy</h1>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="post">
        Käyttäjätunnus: <input type="text" name="username" required><br><br>
        Salasana: <input type="password" name="password1" required><br><br>
        Salasana uudelleen: <input type="password" name="password2" required><br><br>
        <button type="submit" name="register">Rekisteröidy</button>
    </form>
    <p><a href="login.php">Kirjaudu sisään</a></p>
    
</body>
</html>


