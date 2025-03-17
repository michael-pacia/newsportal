<?php 
session_start();
include('includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    <title>LifeRadio | Categories</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    
    <!-- Your existing styles -->
    <style>
        /* Keep all your existing styles here */
    </style>
</head>

<body>
    <!-- Navigation -->
    <?php include('includes/header.php');?>

    <!-- News Ticker -->
    <div class="news-ticker">
        <!-- Keep your existing news ticker code -->
    </div>

    <!-- Page Content -->
    <div class="container">
        <div class="row" style="margin-top: 4%">
            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php 
                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $no_of_records_per_page = 4;
                $offset = ($pageno-1) * $no_of_records_per_page;

                // Add search functionality
                $search_condition = "";
                if(isset($_GET['search']) && !empty($_GET['search'])) {
                    $search_term = mysqli_real_escape_string($con, $_GET['search']);
                    
                    // Check if search term is a date
                    $date_pattern = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
                    if (preg_match($date_pattern, $search_term)) {
                        // If it's a date, search in PostingDate
                        $search_condition = " AND DATE(tblposts.PostingDate) = '$search_term'";
                    } else {
                        // If not a date, search in other fields including author
                        $search_condition = " AND (
                            tblposts.PostTitle LIKE '%$search_term%' 
                            OR tblposts.PostDetails LIKE '%$search_term%'
                            OR tblposts.Place LIKE '%$search_term%'
                            OR tblcategory.CategoryName LIKE '%$search_term%'
                            OR tblsubcategory.Subcategory LIKE '%$search_term%'
                            OR tblposts.postedBy LIKE '%$search_term%'
                            OR DATE_FORMAT(tblposts.PostingDate, '%M %d, %Y') LIKE '%$search_term%'
                            OR DATE_FORMAT(tblposts.PostingDate, '%M %Y') LIKE '%$search_term%'
                            OR DATE_FORMAT(tblposts.PostingDate, '%Y') LIKE '%$search_term%'
                        )";
                    }
                }

                // Update total pages count for search results
                $total_pages_sql = "SELECT COUNT(*) FROM tblposts 
                                   LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
                                   LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
                                   WHERE tblposts.Is_Active = 1" . $search_condition;
                $result = mysqli_query($con, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                // Add search results heading with search tips
                if(isset($_GET['search']) && !empty($_GET['search'])) {
                    echo '<div class="alert alert-info mt-4">
                            <h4>Search Results for: "'.htmlentities($_GET['search']).'"</h4>
                            <p>'.$total_rows.' result(s) found</p>
                            <hr>
                            <p class="mb-0"><small>Search Tips:
                                <ul class="mb-0">
                                    <li>Search by date using format: YYYY-MM-DD (e.g., 2024-03-20)</li>
                                    <li>Search by month and year: March 2024</li>
                                    <li>Search by year: 2024</li>
                                    <li>Search by author name</li>
                                </ul>
                            </small></p>
                          </div>';
                }

                $query = mysqli_query($con,"SELECT tblposts.id as pid, 
                                          tblposts.PostTitle as posttitle, 
                                          tblposts.PostImage,
                                          tblposts.Place as place,
                                          tblposts.postedBy,
                                          tblcategory.CategoryName as category,
                                          tblcategory.id as cid,
                                          tblsubcategory.Subcategory as subcategory,
                                          tblposts.PostDetails as postdetails,
                                          tblposts.PostingDate as postingdate,
                                          tblposts.PostUrl as url 
                                    FROM tblposts 
                                    LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
                                    LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
                                    WHERE tblposts.Is_Active = 1" . $search_condition . " 
                                    ORDER BY tblposts.PostingDate DESC 
                                    LIMIT $offset, $no_of_records_per_page");

                while ($row = mysqli_fetch_array($query)) {
                ?>
                    <div class="card mb-4">
                        <div class="position-relative">
                            <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage'] ?? 'default.jpg');?>" alt="<?php echo htmlentities($row['posttitle'] ?? 'No Title');?>">
                            <?php if(!empty($row['place'])) { ?>
                            <div class="news-category place-badge" style="right: 15px; left: auto; background-color: skyblue;">
                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlentities($row['place']); ?>
                            </div>
                            <?php } ?>
                            <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.5) 60%, transparent); padding: 30px 20px 20px;">
                                <h2 class="card-title" style="color: white; margin-bottom: 0;"><?php echo htmlentities($row['posttitle'] ?? 'No Title');?></h2>
                                <div class="mt-2" style="color: rgba(255,255,255,0.8);">
                                    <small>
                                        <i class="fas fa-user"></i> <?php echo htmlentities($row['postedBy']); ?> | 
                                        <i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($row['postingdate'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" class="btn btn-primary rounded-pill">Read More &rarr;</a>
                        </div>
                    </div>
                <?php } ?>

                <!-- Pagination -->
                <?php if($total_pages > 1) { ?>
                    <ul class="pagination justify-content-center mb-4">
                        <?php 
                        $search_param = isset($_GET['search']) ? "&search=".urlencode($_GET['search']) : "";
                        if($pageno > 1) { ?>
                            <li class="page-item"><a href="?pageno=1<?php echo $search_param; ?>" class="page-link">First</a></li>
                            <li class="page-item">
                                <a href="?pageno=<?php echo ($pageno - 1).$search_param; ?>" class="page-link">Prev</a>
                            </li>
                        <?php } ?>
                        <?php 
                        $posts_remaining = $total_rows - ($pageno * $no_of_records_per_page);
                        if($posts_remaining > 0) { ?>
                            <li class="page-item">
                                <a href="?pageno=<?php echo ($pageno + 1).$search_param; ?>" class="page-link">Next</a>
                            </li>
                            <li class="page-item"><a href="?pageno=<?php echo $total_pages.$search_param; ?>" class="page-link">Last</a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>

            <!-- Sidebar Widgets Column -->
            <?php include('includes/sidebar.php');?>
        </div>

        <!-- Latest News Section -->
        <div class="row">
            <div class="col-12">
                <h2 class="mt-4"><b>Latest News</b></h2>
                <br>
                <?php include('includes/latest_news.php'); ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php');?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Your existing scripts -->
    <script>
        // Keep your existing JavaScript code here
    </script>
</body>
</html>
