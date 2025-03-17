<?php
include('includes/config.php');

if (isset($_POST['categoryId']) && isset($_POST['offset'])) {
    $categoryId = intval($_POST['categoryId']);
    $offset = intval($_POST['offset']);
    
    // Get total posts count for this category
    $totalQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM tblposts 
                                    WHERE Is_Active = 1 AND CategoryId = $categoryId");
    $totalPosts = mysqli_fetch_assoc($totalQuery)['total'];
    
    // Adjust offset if it exceeds total posts
    $offset = $offset % $totalPosts;
    
    $query = mysqli_query($con, "SELECT tblposts.id, 
                                tblposts.PostTitle, 
                                tblposts.PostImage, 
                                tblposts.PostingDate, 
                                tblsubcategory.Subcategory 
                         FROM tblposts 
                         LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
                         WHERE tblposts.Is_Active = 1 
                         AND tblposts.CategoryId = $categoryId 
                         ORDER BY tblposts.PostingDate DESC 
                         LIMIT 3 OFFSET $offset");
    
    $posts = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $posts[] = array(
            'id' => $row['id'],
            'PostTitle' => htmlentities($row['PostTitle']),
            'PostImage' => htmlentities($row['PostImage']),
            'PostingDate' => htmlentities($row['PostingDate']),
            'Subcategory' => htmlentities($row['Subcategory'])
        );
    }
    
    echo json_encode(array(
        'posts' => $posts,
        'totalPosts' => $totalPosts
    ));
}
?> 