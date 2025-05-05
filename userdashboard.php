<?php
    // include 'backend\connect.php';
    include 'supabase.php';
    include 'auth.php';

    // session_start();

    $name = $_SESSION['name'];
    $email = $_SESSION['email'];

    $sql2 = "SELECT path FROM profile_photos WHERE email = '$email'";
    $result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name . "Dashboard" ?></title>
</head>
<body>
    <div>

<div style="width: 50%; background-color: rgb(181, 226, 222); margin-left: 30%; border-radius: 5px; border-style: none; padding: 2px; font-family: tahoma; overflow: hidden;">

    <h3 style="margin-left: 2%; background-color: rgb(181, 226, 222); text-align: center; font-family: tahoma; border-radius:10px; float: left; font-size: 34px;">

    <?php echo $name?></h3>

    <h3 style="margin-right: 2%; background-color: rgb(181, 226, 222); text-align: center; font-family: tahoma; border-radius:10px; float: right;">

    <?php
        if ($result2->num_rows >= 0) {
            while ($row = $result2->fetch_assoc()) {
                echo "<img src='" . $row["path"] . "'alt='image' width='100px' height='100px' style='border-radius: 50px;'>";
            }
        } else {
            echo "Profile Photo of ". $name;
        }
    ?>

    </h3>
</div>

<h2 style="width : 50%; margin-left: 30%; background-color: rgb(181, 226, 222); text-align: center; font-family: tahoma; border-radius:10px; display: inline-block">
    <?php
        if ($result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {
                echo "<img src='" . $row["path"] . "'alt='image' width='50px' height='50px'>";
            }
        } else {
            echo "Profile Photo of ". $name;
        }
    ?>

</h2>

<br>

<div style="width: 50%; background-color: rgb(181, 226, 222); margin-left: 30%; border-radius: 5px; border-style: none; padding: 5px; font-family: tahoma;">

    <button style="background-color: black; border-style: none; border-radius: 5px; width: 10%; font-family: tahoma; color: white"><a style="color: white; text-decoration:none;" href="upload.php">Upload Profile Photo</button>
    
    <button style="background-color: black; border-style: none; border-radius: 5px; width: 10%; font-family: tahoma; color: white"><a style="color: white; text-decoration:none;" href="changepassword.php">Change Password</button>

    <button style="background-color: black; border-style: none; border-radius: 5px; width: 10%; font-family: tahoma; color: white"><a style="color: white; text-decoration:none;" href="login.html">Logout</button>
    
</div>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $new_content = $_POST['new_content'];

        $sql = "INSERT INTO blog_t(user_id, content) VALUES('$id', '$new_content')";
        $conn->query($sql);
        header('Location: userdashboard.php');
    }
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $name;
?>
</div>

</body>
</html>