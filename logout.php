<?php
//Käynnistetään sessio jos ei ole jo käynnissä
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];

//Poistetaan sessioeväste, jos sellainen on
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

//Lopetetaan sessio
session_destroy();

//Ohjataan käyttäjä takaisin etusivulle
header("Location: login.php");
exit;
