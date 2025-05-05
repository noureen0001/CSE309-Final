<?php
session_start();

function is_superuser() {
    return isset($_SESSION['username']) && $_SESSION['username'] === 'root';
}

function ensure_logged_in() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}
?>
