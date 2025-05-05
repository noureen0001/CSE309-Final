<?php
session_start();

// Supabase credentials
$supabase_url = getenv('supabase_url');
$supabase_key = getenv('supabase_key');

function fetchAdmins() {
    global $supabase_url, $supabase_key;
    $url = "$supabase_url/rest/v1/admin?select=*";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_name = $_POST['admin_name'] ?? '';
    $input_password = $_POST['admin_password'] ?? '';
    $admins = fetchAdmins();

    foreach ($admins as $admin) {
        if ($admin['name'] === $input_name && $admin['password'] === $input_password) {
            $_SESSION['admin_name'] = $input_name;
            header("Location: admin_dashboard.php");
            exit;
        }
    }

    $error = "Invalid username or password!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            border-radius: 8px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center text-primary mb-4">Admin Login</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Admin Name</label>
                <input type="text" name="admin_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="admin_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
