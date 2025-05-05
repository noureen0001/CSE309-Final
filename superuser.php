<?php
include 'auth.php';
ensure_logged_in();

if (!is_superuser()) {
    echo "Access denied.";
    exit();
}

echo "<h1>Welcome Superuser</h1>";
echo "<a href='all-link.php'>Go to All-Link</a><br>";
