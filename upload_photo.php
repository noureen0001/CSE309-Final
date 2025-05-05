<?php
include 'supabase.php';
include 'auth.php';
ensure_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $filename = $_FILES['photo']['name'];
    $tmp = $_FILES['photo']['tmp_name'];

    move_uploaded_file($tmp, "assets/photos/" . $filename);
    $path = "assets/photos/" . $filename;

    $supabase->from('profile_photos')->insert([
        'email' => $_SESSION['username'],
        'photo_path' => $path
    ])->execute();

    echo "Uploaded!";
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="photo" required>
    <button>Upload</button>
</form>
