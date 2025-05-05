<?php
session_start();
include 'supabase.php';
include 'auth.php';

$name = $_SESSION['name'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($name); ?> | Upload</title>
</head>
<body>
    <h2 style="width : 50%; margin-left: 30%; background-color: rgb(181, 226, 222); text-align: center;"><?php echo htmlspecialchars($name); ?></h2>

    <div style="width: 50%; background-color: rgb(181, 226, 222); margin-left: 30%; border-radius: 5px; padding: 2px;">
        <form action="#" method="post" enctype="multipart/form-data">
            <p>Select image to upload:</p>
            <input type="file" name="fileToUpload" required>
            <input type="submit" value="Upload Image" name="submit">
        </form><br>

        <button><a href="userdashboard.php">Home</a></button>
        <button><a href="all_images.php">All Images</a></button>
        <button><a href="login.html">Logout</a></button>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $bucket = "photo-for-webapp"; // Your Supabase storage bucket name
    $file = $_FILES["fileToUpload"];
    $filename = uniqid() . "_" . basename($file["name"]);
    $tmp_path = $file["tmp_name"];

    $mime = mime_content_type($tmp_path);

    try {
        // Upload image to Supabase Storage
        $upload = $client->storage->upload("$bucket/$filename", fopen($tmp_path, 'r'), [
            'Content-Type' => $mime,
            'upsert' => true
        ]);

        // Build public URL (make sure bucket is public or signed URL logic needed)
        $public_url = "https://$SUPABASE_PROJECT_REF.supabase.co/storage/v1/object/public/$bucket/$filename";

        // Update profile_photos table
        $update = $client->patch('profile_photos', [
            'headers' => [
                'apikey' => $SUPABASE_API_KEY,
                'Authorization' => 'Bearer ' . $SUPABASE_API_KEY,
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'email' => "eq.$email"
            ],
            'json' => [
                'path' => $public_url
            ]
        ]);

        echo "<p style='color:green;'>Upload successful!</p>";
        header("Location: userdashboard.php");
        exit();
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
