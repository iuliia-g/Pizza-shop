<?php
include "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Muista käynnistää sessio
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_POST['add'])) {
    $id = (int)$_POST['product_id'];
    $qty = (int) $_POST['quantity'];
    if ($qty > 0) {
        if (!isset($_SESSION['cart'] [$id])) {
            $_SESSION['cart'] [$id] = 0;
        }
        $_SESSION['cart'][$id] += $qty;
    }
}

if (isset($_POST['remove'])) {
    $id = (int)$_POST['remove'];
    unset($_SESSION['cart'][$id]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ostoskori</title>
</head>
<body>
    <h1>Ostoskori</h1>

    <?php 
    if (!empty($_SESSION['cart'])){        
        $ids = implode(",", array_keys($_SESSION['cart']));
        $result = $conn->query("SELECT * FROM tuotteet WHERE id IN ($ids)");
        $total = 0;
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $qty = $_SESSION['cart'][$row['id']];
            $line = $qty * $row['hinta'];
            $total += $line;
            echo "<li>{$row['nimi']} ({$qty} kpl) - " . number_format($line, 2) . " €
                <form method='post' style='display: inline'>
                    <button type='submit' name='remove' value='{$row['id']}'>Poista</button>
                </form></li>";
        }
        echo "</ul>";
        echo "<p>Yhteensä: " . number_format($total, 2) . " €</p>";
        echo "<a href='kassalle.php'>Siirry kassalle</a>";
    } else {
        echo "<p>Ostoskori on tyhjä .</p>";

}
?>

<p><a href="index.php">Takaisin kauppaan</a></p>
</body>
</html>
