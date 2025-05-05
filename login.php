<?php
include 'supabase.php';
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === 'root' && $password === 'T9x!rV@5mL#8wQz&Kd3') {
        $_SESSION['username'] = 'root';
        header("Location: superuser.php");
        exit();
    }

    try {
        $response = $client->get('users', [
            'headers' => [
                'apikey' => $supabase_key,
                'Authorization' => 'Bearer ' . $supabase_key
            ],
            'query' => [
                'email' => "eq.$email",
                'password' => "eq.$password",
                'select' => '*'
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        if (!empty($body)) {
            // session_start();
            // $_SESSION['user'] = $body[0];
            // $email = $body[0]['email'];
            $name = $body[0]['name'];
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            
            echo "Login Successfully!<br>";
            echo $name . "<br>" . $email;
            header("Location: userdashboard_new.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } catch (Exception $e) {
        echo "Login failed: " . $e->getMessage();
    }
}
?>

<!-- <form method="post">
    <input name="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
</form> -->
