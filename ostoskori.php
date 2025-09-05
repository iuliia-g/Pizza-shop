<?php
include "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add'])) {
    $id = (int)$_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    if ($qty > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
}

if (isset($_POST['remove'])) {
    $id = (int)$_POST['remove'];
    unset($_SESSION['cart'][$id]);
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Ostoskori</title>
    <link rel="stylesheet" href="tyyli.css">
</head>
<body>
    <main class="cart-container">
        <h1>🛒 Ostoskori</h1>

        <?php 
        if (!empty($_SESSION['cart'])) {
            $ids = implode(",", array_keys($_SESSION['cart']));
            $result = $conn->query("SELECT * FROM tuotteet WHERE id IN ($ids)");
            $total = 0;

            echo "<ul class='cart-list'>";
            while ($row = $result->fetch_assoc()) {
                $qty = $_SESSION['cart'][$row['id']];
                $line = $qty * $row['hinta'];
                $total += $line;

                echo "<li class='cart-item'>
                    <div class='item-info'>
                        <span class='item-name'>" . htmlspecialchars($row['nimi']) . "</span>
                        <span class='item-qty'>({$qty} kpl)</span>
                        <span class='item-price'>" . number_format($line, 2) . " €</span>
                    </div>
                    <form method='post' class='remove-form'>
                        <button type='submit' name='remove' value='{$row['id']}'>Poista</button>
                    </form>
                </li>";
            }
            echo "</ul>";

            echo "<p class='cart-total'>Yhteensä: <strong>" . number_format($total, 2) . " €</strong></p>";
            echo "<p><a href='kassalle.php' class='checkout-link'>✅ Siirry kassalle</a></p>";
        } else {
            echo "<p class='empty-cart'>Ostoskori on tyhjä.</p>";
        }
        ?>

        <p><a href="index.php" class="back-link">⬅️ Takaisin kauppaan</a></p>
    </main>
</body>
</html>
