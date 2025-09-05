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
    <link rel="stylesheet" href="tyyli.css"> 
    <title>Kategoriat</title>
</head>
<body>
    <main>
    <h1>🍕 Kategoriat 🍕</h1>

    <?php if (isset($_SESSION['msg'])): ?>
        <div id="notif"><?= htmlspecialchars($_SESSION['msg']['text']) ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <section class="add-category">
        <h3>Lisää uusi kategoria</h3>
        <form method="post">
            <label for="nimi">Nimi:</label>
            <input type="text" name="nimi" required>
            <button type="submit" name="add">Lisää</button>
        </form>
    </section>

    <section class="category-table">
        <h3>Nykyiset kategoriat</h3>
        <table>
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
    </section>
</main>
</body>
</html>