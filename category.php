<?php 
session_start();
error_reporting(0);
include('includes/config.php');

    ?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" href="includes/images/life2.png">
    <link rel="apple-touch-icon" href="includes/images/life2.png">
    <!-- App title -->
    <title>LifeRadio | Categories</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <style>
    /* Enhanced Card Design */
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: #ffffff;
        margin-bottom: 30px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .position-relative {
        position: relative;
        overflow: hidden;
    }

    .card-img-top {
        height: 400px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s ease;
    }

    .card:hover .card-img-top {
        transform: scale(1.05);
    }

    .position-absolute {
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0.7) 70%, transparent);
        padding: 50px 25px 25px;
        transition: all 0.3s ease;
    }

    .card-title {
        color: #ffffff;
        font-size: 2.2rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 15px;
        line-height: 1.3;
        transition: transform 0.3s ease;
    }

    .card:hover .card-title {
        transform: translateY(-5px);
    }

    .news-category {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        color: #333;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 2;
    }

    .card-body {
        padding: 25px;
        background: #ffffff;
    }

    .btn-primary {
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        background: #007bff;
        border: none;
        box-shadow: 0 4px 15px rgba(0,123,255,0.2);
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,123,255,0.3);
    }

    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 15px 25px;
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .card-footer i {
        margin-right: 8px;
        color: #007bff;
    }

    /* Category Heading */
    .category-heading {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #007bff;
        position: relative;
    }

    .category-heading::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100px;
        height: 3px;
        background: #0056b3;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card-img-top {
            height: 300px;
        }
        
        .card-title {
            font-size: 1.8rem;
        }
        
        .position-absolute {
            padding: 40px 20px 20px;
        }
        
        .category-heading {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .card-img-top {
            height: 250px;
        }
        
        .card-title {
            font-size: 1.5rem;
        }
        
        .position-absolute {
            padding: 30px 15px 15px;
        }
        
        .category-heading {
            font-size: 1.8rem;
        }
        
        .btn-primary {
            width: 100%;
            text-align: center;
        }
    }

    /* Pagination Enhancement */
    .pagination {
        gap: 5px;
        justify-content: center;
        margin-top: 40px;
    }

    .page-link {
        border-radius: 8px;
        padding: 10px 18px;
        color: #007bff;
        font-weight: 500;
        border: none;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: #007bff;
        color: #fff;
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 15px rgba(0,123,255,0.2);
    }

    /* No Results Enhancement */
    .no-results {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .no-results h3 {
        color: #dc3545;
        font-size: 1.8rem;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .no-results p {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .no-results .suggestions {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        max-width: 400px;
        margin: 0 auto;
    }

    .no-results .suggestions h4 {
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .no-results .suggestions ul {
        list-style-type: none;
        padding: 0;
    }

    .no-results .suggestions li {
        color: #6c757d;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .no-results .suggestions li:last-child {
        border-bottom: none;
    }

    /* Video container styles */
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        margin: 20px 0;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(2, 1, 1, 0.1);
    }

    .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 1;
    }

    .video-thumbnail {
        position: relative;
        cursor: pointer;
    }

    .video-thumbnail::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(0,0,0,0.7);
        border-radius: 50%;
        z-index: 1;
    }

    .video-thumbnail::after {
        content: '▶';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 24px;
        z-index: 2;
    }

    /* Add these styles to your existing CSS */
    .video-link-container {
        text-align: center;
        margin-top: 15px;
    }

    .video-link-container .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .video-link-container .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .video-link-container .fas {
        margin-right: 5px;
    }

    /* Add these styles to your existing CSS */
    .video-container.featured-video {
        padding-top: 75%; /* Taller aspect ratio for featured video */
        margin: 0 auto;
        max-width: 1000px;
    }

    .video-title {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
        margin-top: -5px;
    }

    .video-title h3 {
        color: #333;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .video-title p {
        color: #666;
        margin-bottom: 0;
    }

    /* Responsive adjustments for featured video */
    @media (max-width: 768px) {
        .video-container.featured-video {
            padding-top: 85%;
        }

        .video-title h3 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 576px) {
        .video-container.featured-video {
            padding-top: 100%;
        }

        .video-title h3 {
            font-size: 1.2rem;
        }
    }
    </style>

  </head>

  <body>

    <!-- Navigation -->
   <?php include('includes/header.php');?>

    <!-- Page Content -->
    <div class="container">


     
      <div class="row" style="margin-top: 4%">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

          <!-- Blog Post -->
<?php 
        if($_GET['catid']!=''){
$_SESSION['catid']=intval($_GET['catid']);
}
             






     if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 8;
        $offset = ($pageno-1) * $no_of_records_per_page;


        $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
        $result = mysqli_query($con,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);


$query=mysqli_query($con,"select tblposts.id as pid,tblposts.PostTitle as posttitle,
    tblposts.PostImage,tblposts.PostVideo,tblcategory.CategoryName as category,
    tblcategory.id as cid,tblsubcategory.Subcategory as subcategory,
    tblposts.PostDetails as postdetails,tblposts.PostingDate as postingdate,
    tblposts.PostUrl as url from tblposts left join tblcategory on 
    tblcategory.id=tblposts.CategoryId left join tblsubcategory on 
    tblsubcategory.SubCategoryId=tblposts.SubCategoryId where 
    tblposts.CategoryId='".$_SESSION['catid']."' and tblposts.Is_Active=1 
    " . (isset($_GET['video_id']) ? "and tblposts.id='".intval($_GET['video_id'])."'" : "") . "
    order by tblposts.id desc LIMIT $offset, $no_of_records_per_page");

$rowcount=mysqli_num_rows($query);

// Get category name first
$cat_query = mysqli_query($con, "SELECT CategoryName FROM tblcategory WHERE id='".$_SESSION['catid']."'");
$cat_row = mysqli_fetch_array($cat_query);
$is_blog = ($cat_row && strtolower($cat_row['CategoryName']) === 'blog');

if($cat_row) {
?>
    <h1 class="category-heading">
        <?php echo htmlentities($cat_row['CategoryName'])." News"; ?>
    </h1>
    <br>
<?php
}

if($rowcount==0)
{
?>
<div class="no-results">
    <h3>No Posts Found</h3>
    <p>Sorry, we couldn't find any posts in this category.</p>
    <div class="suggestions">
        <h4>Suggestions:</h4>
        <ul>
            <li>Check back later for new posts</li>
            <li>Try browsing other categories</li>
            <li>Visit our homepage for latest news</li>
        </ul>
    </div>
</div>
<?php
} else {
while ($row=mysqli_fetch_array($query)) {
?>
          <div class="card mb-4">
            <div class="position-relative">
              <?php if(!$is_blog || !isset($_GET['video_id'])): ?>
              <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage'] ?? 'default.jpg');?>" alt="<?php echo htmlentities($row['posttitle'] ?? 'No Title');?>">
              <div class="position-absolute">
                <h2 class="card-title"><?php echo htmlentities($row['posttitle'] ?? 'No Title');?></h2>
              </div>
              <?php endif; ?>
              <?php if($row['subcategory'] && !$is_blog): ?>
              <div class="news-category">
                <?php echo htmlentities($row['subcategory']); ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <?php if($is_blog): ?>
              <h2 class="card-title" style="color: #000000; font-size: 1.8rem; margin-bottom: 1.5rem;"><?php echo htmlentities($row['posttitle'] ?? 'No Title');?></h2>
              <?php endif; ?>
              <?php if($is_blog && $row['PostVideo'] != ""): ?>
              <div class="video-container<?php echo (isset($_GET['video_id']) && $_GET['video_id'] == $row['pid']) ? ' featured-video' : ''; ?>">
                <?php if(isset($_GET['video_id']) && $_GET['video_id'] == $row['pid']): ?>
                <span class="video-badge">Now Playing</span>
                <video width="100%" controls autoplay>
                  <source src="admin/postvideos/<?php echo htmlentities($row['PostVideo']);?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
                <div class="video-title mt-3">
                  <h3><?php echo htmlentities($row['posttitle']); ?></h3>
                  <p class="text-muted">Posted on <?php 
                    $date = new DateTime($row['postingdate']); 
                    echo $date->format('F j, Y'); 
                  ?></p>
                </div>
                <?php else: ?>
                <span class="video-badge">Video</span>
                <video width="100%" controls>
                  <source src="admin/postvideos/<?php echo htmlentities($row['PostVideo']);?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
                <?php endif; ?>
              </div>
              <?php endif; ?>
              <br>
              <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="btn btn-primary rounded-pill">Read More &rarr;</a>
            </div>
            <div class="card-footer text-muted">
              <i class="far fa-clock"></i> Posted on <?php 
                $date = new DateTime($row['postingdate']); 
                echo $date->format('F j, Y'); 
              ?>
            </div>
          </div>
<?php } ?>

    <?php 
    // Get the count of posts for the next page
    $next_page_offset = ($pageno * $no_of_records_per_page);
    $next_page_query = mysqli_query($con, "SELECT COUNT(*) FROM tblposts WHERE CategoryId='".$_SESSION['catid']."' AND Is_Active=1 LIMIT $next_page_offset, $no_of_records_per_page");
    $next_page_count = mysqli_fetch_array($next_page_query)[0];

    // Only show pagination if there are posts on the next page
    if($next_page_count > 0) { ?>
        <ul class="pagination justify-content-center mb-4">
            <?php 
            // Show First/Prev only if not on first page
            if($pageno > 1) { ?>
                <li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>
                <li class="page-item">
                    <a href="?pageno=<?php echo $pageno - 1; ?>" class="page-link">Prev</a>
                </li>
            <?php }

            // Display page numbers
            $start_page = max(1, min($pageno - 2, $total_pages - 4));
            $end_page = min($total_pages, max(5, $pageno + 2));
            
            // Ensure we always show at least 5 pages when possible
            if ($end_page - $start_page + 1 < 5 && $total_pages >= 5) {
                if ($pageno > $total_pages - 2) {
                    $start_page = max(1, $total_pages - 4);
                } else {
                    $end_page = min($total_pages, $start_page + 4);
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++) { ?>
                <li class="page-item <?php echo ($i == $pageno) ? 'active' : ''; ?>">
                    <a href="?pageno=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                </li>
            <?php }

            // Show Next/Last only if not on last page
            if($pageno < $total_pages) { ?>
                <li class="page-item">
                    <a href="?pageno=<?php echo $pageno + 1; ?>" class="page-link">Next</a>
                </li>
                <li class="page-item">
                    <a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
<?php } ?>
       

      

          <!-- Pagination -->




        </div>

        <!-- Sidebar Widgets Column -->
      <?php include('includes/other_news.php');?>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
      <?php include('includes/footer.php');?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
