<?php
include 'supabase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    try {
        $response = $client->post('users', [
            'json' => [$user]
        ]);

        $body = json_decode($response->getBody(), true);
        header("Location: login.php");
    } catch (Exception $e) {
        echo "Registration failed: " . $e->getMessage();
    }
}
?>

<form method="post">
    <input name="name" required>
    <input name="email" type="email" required>
    <input name="password" type="password" required>
    <button>Register</button>
</form>
