<?php 
session_start();
include('includes/config.php');

// Move view counter update before any other logic
if(isset($_GET['nid'])) {
    $postid = intval($_GET['nid']);
    mysqli_query($con, "UPDATE tblposts SET ViewCount = ViewCount + 1 WHERE id = '$postid'");
}

//Genrating CSRF Token
if (empty($_SESSION['token'])) {
 $_SESSION['token'] = bin2hex(random_bytes(32));
}

if(isset($_POST['submit']))
{
    //Verifying CSRF Token
    if (!empty($_POST['csrftoken'])) {
        if (hash_equals($_SESSION['token'], $_POST['csrftoken'])) {
            $name=$_POST['name'];
            $email=$_POST['email'];
            $comment=$_POST['comment'];
            $postid=intval($_GET['nid']);
            $st1='0';
            $query=mysqli_query($con,"insert into tblcomments(postId,name,email,comment,status) values('$postid','$name','$email','$comment','$st1')");
            if($query):
                echo "<script>alert('comment successfully submit. Comment will be display after admin review ');</script>";
                unset($_SESSION['token']);
            else:
                echo "<script>alert('Something went wrong. Please try again.');</script>";  
            endif;
        }
    }
}

// Open Graph Meta Tags for Facebook
if(isset($_GET['nid'])) {
    $pid = intval($_GET['nid']);
    $query = mysqli_query($con, "SELECT p.*, c.CategoryName 
        FROM tblposts p 
        LEFT JOIN tblcategory c ON c.id = p.CategoryId 
        WHERE p.id='$pid' AND p.Is_Active=1");
    if(mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);
        // Get absolute URL for the image
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $image_url = $protocol . $host . "/admin/postimages/" . $row['PostImage'];
        ?>
        <meta property="og:title" content="<?php echo htmlentities($row['PostTitle']); ?>">
        <meta property="og:description" content="<?php echo htmlentities(substr(strip_tags($row['PostDetails']), 0, 200)).'...'; ?>">
        <meta property="og:image" content="<?php echo $image_url; ?>">
        <meta property="og:url" content="<?php echo $protocol . $host . $_SERVER['REQUEST_URI']; ?>">
        <meta property="og:type" content="article">
        <meta property="og:site_name" content="News Portal">
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo htmlentities($row['PostTitle']); ?>">
        <meta name="twitter:description" content="<?php echo htmlentities(substr(strip_tags($row['PostDetails']), 0, 200)).'...'; ?>">
        <meta name="twitter:image" content="<?php echo $image_url; ?>">
        <?php
    }
}
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
    <title>LifeRadio | Details</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    /* Enhanced responsive styles for news details page */
    .featured-image-container {
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio for better image fit */
        overflow: hidden;
        background-color: #000;
        margin: 0;
        border-radius: 4px 4px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-image-container img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        background-color: #000;
        display: block;
    }

    /* Responsive adjustments for featured image */
    @media (max-width: 768px) {
        .featured-image-container {
            padding-top: 85%; /* Taller aspect ratio for tablets */
        }
    }

    @media (max-width: 576px) {
        .featured-image-container {
            padding-top: 100%; /* Square aspect ratio for mobile */
        }
    }

    /* Card adjustments */
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        background-color: #fff;
        margin-bottom: 30px;
        border-radius: 8px;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Title adjustments */
    .card-title {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.8rem;
        line-height: 1.3;
        color: #333;
    }

    /* Image hover effect */
    .featured-image-container:hover img {
        transform: translate(-50%, -50%) scale(1.05);
    }

    /* Ensure image is visible */
    .featured-image-container img {
        opacity: 1;
        visibility: visible;
    }

    .post-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .post-meta a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s;
    }

    .post-meta a:hover {
        color: #0056b3;
    }

    .post-content {
    display: block !important;
    visibility: visible !important;
    color: #333 !important;
}


    /* Comments section styling */
    .comment-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .comment-list {
        margin-top: 30px;
    }

    .comment-item {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Article header */
        .card-title {
            font-size: 1.5rem;
            line-height: 1.3;
            margin: 15px 0;
        }

        /* Featured image */
        .featured-image-container {
            padding-top: 75%; /* 4:3 Aspect Ratio for tablets */
        }

        .featured-image-container img {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
        }

        /* Post metadata */
        .post-meta {
            font-size: 0.85rem;
            margin: 10px 0;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        /* Article content */
        .post-content {
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Comments section */
        .card-body {
            padding: 15px;
        }

        .comment-form {
            padding: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
        }

        .media {
            flex-direction: column;
        }

        .media img {
            margin-bottom: 10px;
            width: 40px;
            height: 40px;
        }

        .media-body {
            width: 100%;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .card-title {
            font-size: 1.3rem;
        }

        .featured-image-container img {
            max-height: 250px;
        }

        .post-meta {
            font-size: 0.8rem;
        }

        .post-content {
            font-size: 0.95rem;
        }

        /* Comment adjustments */
        .media-body h5 {
            font-size: 1rem;
        }

        .media-body p {
            font-size: 0.9rem;
        }
    }

    /* Medium devices (tablets) */
    @media (min-width: 481px) and (max-width: 768px) {
        .card-title {
            font-size: 1.7rem;
        }

        .featured-image-container img {
            max-height: 350px;
        }
    }

    /* Large devices */
    @media (min-width: 769px) {
        .container {
            max-width: 1140px;
        }

        .featured-image-container {
            margin: -1.25rem -1.25rem 1.25rem;
        }

        .featured-image-container img {
            max-height: 500px;
            width: 100%;
            object-fit: cover;
        }
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .btn,
        .nav-link {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .post-content {
            color: #e1e1e1;
        }

        .comment-form {
            background: #2d2d2d;
        }

        .comment-item {
            background: #333;
            color: #e1e1e1;
        }
    }

    /* Print styles */
    @media print {
        .comment-section,
        .sidebar,
        .nav,
        .footer {
            display: none;
        }

        .post-content {
            font-size: 12pt;
            line-height: 1.5;
        }
    }

    /* Accessibility improvements */
    .form-control:focus {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }

    /* Loading states */
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }

    /* Image loading */
    .featured-image-container img {
        transition: opacity 0.3s;
    }

    .featured-image-container img[loading] {
        opacity: 0.5;
    }

    /* Enhanced post metadata styling */
    .post-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        color: #6c757d;
        margin: 1rem 0 1.5rem;
    }

    .post-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .post-meta i {
        font-size: 0.9rem;
        color: #007bff;
    }

    .post-meta a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .post-meta a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .separator {
        color: #dee2e6;
    }

    /* Responsive adjustments for post meta */
    @media (max-width: 576px) {
        .post-meta {
            font-size: 0.85rem;
            gap: 8px;
        }
        
        .post-meta i {
            font-size: 0.8rem;
        }
    }

    /* Add this to your existing CSS styles section */
    .social-share {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        transform: scale(1.1);
    }

    .fa-facebook {
        color: #1877f2;
    }

    .fa-telegram {
        color: #0088cc;
    }

    .fa-whatsapp {
        color: #25D366;
    }

    /* Dark mode support for social icons */
    @media (prefers-color-scheme: dark) {
        .social-icon {
            background: rgba(255, 255, 255, 0.1);
        }
    }

    /* Update the social sharing styles to maintain left alignment */
    .social-share-container {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .social-share-container h5 {
        margin-bottom: 15px;
        color: #333;
        font-size: 1rem;
    }

    .social-share-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fff;
        text-decoration: none;
        transition: transform 0.2s ease;
    }

    .social-icon:hover {
        transform: scale(1.1);
        color: #fff;
    }

    .social-icon i {
        font-size: 20px;
    }

    /* Social icons background colors */
    .social-icon:nth-child(1) {
        background-color: #1877f2; /* Facebook */
    }

    .social-icon:nth-child(2) {
        background-color: #0088cc; /* Telegram */
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .social-share-container h5 {
            color: #e1e1e1;
        }
    }

    /* Mobile responsiveness */
    @media (max-width: 576px) {
        .social-share-buttons {
            justify-content: flex-start;
        }

        .social-icon {
            width: 45px;
            height: 45px;
        }
    }

    /* Medium devices */
    @media (min-width: 577px) and (max-width: 768px) {
        .social-share-buttons {
            justify-content: flex-start;
        }
    }

    /* Add these styles to your existing CSS */
    .video-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden;
        background-color: #000;
        margin: 0;
        border-radius: 4px 4px 0 0;
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

    @media (max-width: 768px) {
        .video-container {
            padding-top: 75%; /* Taller aspect ratio for tablets */
        }
    }

    @media (max-width: 576px) {
        .video-container {
            padding-top: 100%; /* Square aspect ratio for mobile */
        }
    }

    /* Add these styles to your existing CSS section */
    .video-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin: 30px 0;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .video-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .video-preview-container {
        text-align: center;
    }

    .video-preview-content h4 {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .video-preview-content h4 i {
        margin-right: 10px;
        font-size: 1.8rem;
    }

    .btn-watch-video {
        display: inline-block;
        padding: 15px 30px;
        background: linear-gradient(45deg, #2196F3, #1976D2);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 500;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-watch-video:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-watch-video .btn-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-watch-video i {
        font-size: 1.3rem;
    }

    .btn-watch-video .btn-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent,
            rgba(255, 255, 255, 0.3),
            transparent
        );
        transition: 0.5s;
    }

    .btn-watch-video:hover .btn-shine {
        left: 100%;
    }

    @media (max-width: 768px) {
        .video-section {
            padding: 20px;
            margin: 20px 0;
        }

        .video-preview-content h4 {
            font-size: 1.3rem;
        }

        .btn-watch-video {
            padding: 12px 25px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .video-section {
            padding: 15px;
        }

        .video-preview-content h4 {
            font-size: 1.2rem;
        }

        .btn-watch-video {
            width: 100%;
            padding: 12px 20px;
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
          $pid=intval($_GET['nid']);
          $query=mysqli_query($con,"select tblposts.PostTitle as posttitle, 
              tblposts.PostImage, 
              tblposts.PostVideo,
              tblposts.ViewCount as views, 
              tblcategory.CategoryName as category, 
              tblcategory.id as cid, 
              tblsubcategory.Subcategory as subcategory, 
              tblposts.PostDetails as postdetails, 
              tblposts.PostingDate as postingdate, 
              tblposts.PostUrl as url, 
              tblposts.Author as author
              from tblposts 
              left join tblcategory on tblcategory.id=tblposts.CategoryId 
              left join tblsubcategory on tblsubcategory.SubCategoryId=tblposts.SubCategoryId 
              where tblposts.id='$pid'");
          while ($row=mysqli_fetch_array($query)) {
          ?>

          <div class="card mb-4">
            <!-- Featured Image -->
            <div class="featured-image-container">
              <img class="img-fluid" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>"
                   alt="<?php echo htmlentities($row['posttitle']);?>">
            </div>
            <div class="card-body">
              <!-- Title and metadata -->
              <h2 class="card-title"><?php echo htmlentities($row['posttitle']);?></h2>
              <p class="post-meta">
                <span class="author-info">
                  <i class="fas fa-user-edit"></i>
                  <a href="search_result.php?searchBy=author&query=<?php echo urlencode($row['author']); ?>"><?php echo htmlentities($row['author']);?></a>
                </span>
                <span class="category-info">
                  <i class="fas fa-globe"></i>
                  <a href="search_result.php?searchBy=subcategory&query=<?php echo urlencode($row['subcategory']); ?>"><?php echo htmlentities($row['subcategory']);?></a>
                </span>
                <span class="date-info">
                  <i class="fas fa-calendar-alt"></i>
                  <a href="search_result.php?searchBy=date&query=<?php echo urlencode($row['postingdate']); ?>">
                    <?php 
                      $date = new DateTime($row['postingdate']);
                      echo $date->format('F j, Y \a\t g:i A'); 
                    ?>
                  </a>
                </span>
              </p>
  
              <!-- Post content with enhanced typography -->
             <div class="post-content" style="font-size: 1.1rem; line-height: 1.8;">
    <?php 
        $pt = $row['postdetails']; 
        echo $pt; // Directly output the post details
    ?>
</div>


              <!-- Video section -->
              <?php if($row['PostVideo'] != ""): ?>
              <div class="video-section mt-4">
                <div class="video-preview-container">
                  <div class="video-preview-content">
                    <p class="text-muted mb-3">Click to watch the actual video in our video section</p>
                    <?php
                    // Get Blog category ID
                    $blog_query = mysqli_query($con, "SELECT id FROM tblcategory WHERE CategoryName = 'Blog' AND Is_Active = 1 LIMIT 1");
                    $blog_row = mysqli_fetch_array($blog_query);
                    if($blog_row) {
                        $blog_cat_id = $blog_row['id'];
                    ?>
                    <a href="category.php?catid=<?php echo $blog_cat_id; ?>&video_id=<?php echo $pid; ?>" 
                       class="btn btn-watch-video">
                      <span class="btn-content">
                        <i class="fas fa-play-circle"></i>
                        <span>Watch Video</span>
                      </span>
                      <span class="btn-shine"></span>
                    </a>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php endif; ?>

              <!-- Social sharing section -->
              <div class="social-share-container">
                <h5>Share:</h5>
                <div class="social-share-buttons">
                  <?php
                  // Get the current URL
                  $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                  // Get the post title and encode it
                  $postTitle = urlencode($row['posttitle']);
                  // Get post description (first 100 characters)
                  $postDesc = urlencode(substr(strip_tags($row['postdetails']), 0, 100) . '...');
                  // Get post image URL
                  $postImage = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/admin/postimages/" . $row['PostImage']);
                  ?>
                  <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($currentURL); ?>&quote=<?php echo $postTitle; ?>&picture=<?php echo $postImage; ?>&description=<?php echo $postDesc; ?>" 
                     target="_blank" 
                     class="social-icon" 
                     title="Share on Facebook"
                     rel="noopener noreferrer">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="https://t.me/share/url?url=<?php echo urlencode($currentURL); ?>&text=<?php echo urlencode($row['posttitle']); ?>" 
                     target="_blank" 
                     class="social-icon" 
                     title="Share on Telegram"
                     onclick="window.open(this.href, 'telegram-share', 'width=580,height=296'); return false;">
                    <i class="fab fa-telegram-plane"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>

          <!-- Moving Comment Section Here - Before Sidebar -->
          <!---Comment Section --->
          <div class="card my-4">
            <h5 class="card-header">Leave a Comment:</h5>
            <div class="card-body">
              <form name="Comment" method="post">
                <input type="hidden" name="csrftoken" value="<?php echo htmlentities($_SESSION['token']); ?>" />
                <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Enter your fullname" required>
                </div>

                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Enter your Valid email" required>
                </div>

                <div class="form-group">
                  <textarea class="form-control" name="comment" rows="3" placeholder="Comment" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
              </form>
            </div>
          </div>

          <!---Comment Display Section --->
          <?php 
          $sts=1;
          $query=mysqli_query($con,"select name,comment,postingDate from tblcomments where postId='$pid' and status='$sts'");
          while ($row=mysqli_fetch_array($query)) {
          ?>
          <div class="media mb-4">
            <img class="d-flex mr-3 rounded-circle" src="images/usericon.png" alt="">
            <div class="media-body">
              <h5 class="mt-0"><?php echo htmlentities($row['name']);?> <br />
                <span style="font-size:11px;"><b>at</b> <?php echo htmlentities($row['postingDate']);?></span>
              </h5>
              <?php echo htmlentities($row['comment']);?>
            </div>
          </div>
          <?php } ?>
        </div>

        <!-- Sidebar Widgets Column -->
        <?php include('includes/other_news.php');?>
      </div>
    </div>

    <?php include('includes/footer.php');?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>