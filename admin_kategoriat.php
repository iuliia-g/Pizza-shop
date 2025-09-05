<?php
session_start();

include "db.php"; // Yhdistetäan tietokantaan (sisaltaa $conn)

/*======= LISAA KATEGORIA ========*/

if(isset($_POST['add'])) {
    $nimi = trim($_POST['nimi']);

    $stmt = $conn->prepare("INSERT INTO kategoriat (nimi) VALUES (?)");
    $stmt->bind_param("s", $nimi);

    if ($stmt->execute()) {
        $_SESSION['msg'] = ["text" => "Kategoria lisättiin onnistuneesti!", "type" => "success"];
    } else {
        $_SESSION['msg'] = ["text" => "Virhe: kategoriaa ei voitu lisätä.", "type" => "error"];
    }
        $stmt->close();

    header("Location: " .$_SERVER['PHP_SELF']);
    exit;
}

/*======= MUOKKAA KATEGORIAA ========*/

if (isset($_POST['muokkaa'])) {
    $id = (int)$_POST['id'];
    $nimi = trim($_POST['nimi']);

    $stmt = $conn->prepare("UPDATE kategoriat SET nimi =? WHERE id =?");
    $stmt->bind_param("si", $nimi, $id);
    
    if ($stmt->execute()) {
        $_SESSION['msg'] = ["text" => "Kategoria muokattiin onnistuneesti!", "type" => "success"];
    } else {
        $_SESSION['msg'] = ["text" => "Virhe: kategoriaa ei voitu muokata.", "type" => "error"];
    }
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/*======= POISTA KATEGORIAA ========*/

if (isset($_POST['poista'])){
    $id = (int)$_POST['id'];
    
    $stmt = $conn->prepare("DELETE FROM kategoriat WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = ["text" => "Kategoria poistettiin onnistuneesti!", "type" => "success"];
    } else {
        $_SESSION['msg'] = ["text" => "Virhe: kategoriaa ei voitu poistaa.", "type" => "error"];
    }
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/*======= HAE KATEGORIAT TIETOKANNASTA ========*/

$kategoriat =$conn->query("SELECT * FROM kategoriat");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriat</title>
</head>
<body>
    <h1>Kategoriat</h1>

    <?php if (isset($_SESSION['msg'])): ?>
        <?php
            $msg = $_SESSION['msg'];
            $color = $msg["type"] === "success" ? "#d4edda" : "#f8d7da";     
            $textColor = $msg["type"] === "success" ? "#155724" : "#721c24";     
            $border = $msg["type"] === "success" ? "#c3e6cb" : "#f5c6cb";     
        ?>

        <div id="notif" style="
            background-color: <?= $color ?>;
            color: <?= $textColor ?>;
            padding: 10px 15px;
            border: 1px solid <?= $border ?>;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        ">

        <?= htmlspecialchars($msg['text']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
    <script>
        setTimeout(() => {
            const box = document.getElementById("notif");
            if (box) {
                box.style.transition = "opacity 0.5s ease";
                box.style.opacity = "0";
                setTimeout(() => box.remove(), 500);
            }
        }, 3000);
        </script>
    <?php endif; ?>

    <!-- LOMAKKE: LISÄÄ UUSI KATEGORIA -->
     <h1>Lisää uusi kategoria</h1>
     <form method="post">  
        Nimi: <input type="text" name="nimi" required>
        <button type="submit" name="add">Lisää</button>
    </form>

    <!-- TAULUKKO: LISTATAAN KAIKKI KATEGORIAT -->
    <h2>Nykyiset kategoriat</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Toiminnot</th>
    </tr>
    <?php while ($row = $kategoriat->fetch_assoc()) { ?>
        <form method="post">
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <input type="text" name="nimi" value="<?= htmlspecialchars($row['nimi']) ?>">
                </td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="muokkaa">Muokkaa</button>
                    <button type="submit" name="poista" onclick="return confirm('Haluatko varmasti poistaa tämän kategorian?')">Poista</button>
                </td>
            </tr>
        </form>
    <?php } ?>
</table>
</body>
</html>