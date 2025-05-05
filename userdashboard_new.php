<?php
// session_start();
include 'supabase.php';
include 'auth.php';

$name = $_SESSION['name'];
$email = $_SESSION['email'];

// Fetch profile photo from Supabase
try {
    $response = $client->get('profile_photos', [
        'headers' => [
            'apikey' => $supabase_key,
            'Authorization' => 'Bearer ' . $supabase_key
        ],
        'query' => [
            'email' => "eq.$email",
            'select' => 'path'
        ]
    ]);
    $photos = json_decode($response->getBody(), true);
} catch (Exception $e) {
    $photos = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($name) . " Dashboard"; ?></title>
</head>
<body>
    <div style="width: 50%; background-color: rgb(181, 226, 222); margin-left: 30%; border-radius: 5px; padding: 2px; font-family: tahoma;">
        <h3 style="text-align: center; font-size: 34px;"><?php echo htmlspecialchars($name); ?></h3>

        <div style="text-align: center;">
            <?php
            if (!empty($photos)) {
                foreach ($photos as $photo) {
                    echo "<img src='" . htmlspecialchars($photo["path"]) . "' alt='image' width='100px' height='100px' style='border-radius: 50px;'>";
                }
            } else {
                echo "Profile Photo of " . htmlspecialchars($name);
            }
            ?>
        </div>
    </div>

    <br>

    <div style="width: 50%; background-color: rgb(181, 226, 222); margin-left: 30%; border-radius: 5px; padding: 5px; font-family: tahoma;">
        <button style="background-color: black; border-radius: 5px; width: 20%;"><a style="color: white; text-decoration: none;" href="upload.php">Upload Profile Photo</a></button>

        <button style="background-color: black; border-radius: 5px; width: 20%;"><a style="color: white; text-decoration: none;" href="changepassword.php">Change Password</a></button>

        <button style="background-color: black; border-radius: 5px; width: 20%;"><a style="color: white; text-decoration: none;" href="login.html">Logout</a></button>
    </div>

    <br>

    <!-- Blog post form -->
    <form method="post" style="width: 50%; margin-left: 30%;">
        <textarea name="new_content" rows="4" cols="60" placeholder="Write a blog post..." required></textarea><br>
        <button type="submit">Post</button>
    </form>

<?php
// Insert blog post to Supabase
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_content = trim($_POST['new_content']);

    try {
        $insert = $client->post('blog_t', [
            'headers' => [
                'apikey' => $SUPABASE_API_KEY,
                'Authorization' => 'Bearer ' . $SUPABASE_API_KEY,
                'Content-Type' => 'application/json'
            ],
            'json' => [[
                'email' => $email,
                'content' => $new_content
            ]]
        ]);

        header('Location: userdashboard.php');
        exit();
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error posting blog: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
</body>
</html>
