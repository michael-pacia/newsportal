<?php
session_start();
include('includes/config.php');

$response = ['success' => false];

if (isset($_POST['csrftoken']) && hash_equals($_SESSION['token'], $_POST['csrftoken'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $postid = intval($_POST['postId']);
    $st1 = '0';

    $query = mysqli_query($con, "INSERT INTO tblcomments(postId, name, email, comment, status) VALUES('$postid', '$name', '$email', '$comment', '$st1')");
    if ($query) {
        $response['success'] = true;
    }
}

echo json_encode($response);
?> 