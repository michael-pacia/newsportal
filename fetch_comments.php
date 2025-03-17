<?php
include('includes/config.php');

$postId = intval($_GET['postId']);
$comments = [];

$query = mysqli_query($con, "SELECT name, comment, postingDate FROM tblcomments WHERE postId='$postId' AND status='1'");
while ($row = mysqli_fetch_assoc($query)) {
    $comments[] = $row;
}

echo json_encode(['comments' => $comments]);
?> 