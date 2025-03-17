<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    $query = "INSERT INTO tblcontactus (Name, Email, Message) VALUES ('$name', '$email', '$message')";
    if (mysqli_query($con, $query)) {
        header("Location: index.php?status=success"); // Redirect with success status
        exit();
    } else {
        header("Location: index.php?status=error"); // Redirect with error status
        exit();
    }
}
?>
