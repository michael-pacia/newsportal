<?php 
session_start();
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>News Category</title>
    <!-- Bootstrap & Custom CSS -->
    <?php include('includes/css-files.php'); ?>
</head>
<body>
    <?php include('includes/header.php');?>
    
    <div class="container">
        <div class="row" style="margin-top: 4%">
            <div class="col-md-8">
                <?php
                // Get category and subcategory info
                $subcatid = intval($_GET['subcatid']);
                $catid = intval($_GET['catid']);
                
                // Fetch subcategory name
                $subcatquery = mysqli_query($con, "SELECT Subcategory FROM tblsubcategory WHERE SubCategoryId = $subcatid");
                $subcatrow = mysqli_fetch_array($subcatquery);
                ?>
                
                <h3 class="mb-4"><?php echo htmlentities($subcatrow['Subcategory']); ?> News</h3>
                
                <?php
                // Pagination setup
                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $no_of_records_per_page = 8;
                $offset = ($pageno-1) * $no_of_records_per_page;
                
                // Get total pages
                $total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE SubCategoryId = $subcatid AND Is_Active = 1";
                $result = mysqli_query($con, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);
                
                // Fetch posts for this subcategory
                $query = mysqli_query($con, "SELECT tblposts.id as pid, 
                                                  tblposts.PostTitle as posttitle,
                                                  tblposts.PostImage,
                                                  tblposts.PostDetails as postdetails,
                                                  tblposts.PostingDate as postingdate
                                           FROM tblposts 
                                           WHERE tblposts.SubCategoryId = $subcatid 
                                           AND tblposts.Is_Active = 1 
                                           ORDER BY tblposts.id DESC 
                                           LIMIT $offset, $no_of_records_per_page");

                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_array($query)) {
                ?>
                    <div class="card mb-4">
                        <div class="position-relative">
                            <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>">
                            <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); padding: 15px;">
                                <h2 class="card-title" style="color: white; margin-bottom: 0;"><?php echo htmlentities($row['posttitle']);?></h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="btn btn-primary rounded-pill">Read More &rarr;</a>
                        </div>
                        <div class="card-footer text-muted">
                            <i class="far fa-clock"></i> Posted on <?php echo htmlentities($row['postingdate']);?>
                        </div>
                    </div>
                <?php }
                } else { ?>
                    <div class="alert alert-info">No news available in this category.</div>
                <?php } ?>

                <!-- Pagination -->
                <ul class="pagination justify-content-center mb-4">
                    <li class="page-item"><a href="?subcatid=<?php echo $subcatid;?>&catid=<?php echo $catid;?>&pageno=1" class="page-link">First</a></li>
                    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?> page-item">
                        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?subcatid=".$subcatid."&catid=".$catid."&pageno=".($pageno - 1); } ?>" class="page-link">Prev</a>
                    </li>
                    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?> page-item">
                        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?subcatid=".$subcatid."&catid=".$catid."&pageno=".($pageno + 1); } ?>" class="page-link">Next</a>
                    </li>
                    <li class="page-item"><a href="?subcatid=<?php echo $subcatid;?>&catid=<?php echo $catid;?>&pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                </ul>
            </div>
            <?php include('includes/sidebar.php');?>
        </div>
    </div>
    <?php include('includes/footer.php');?>
    <!-- JavaScript files -->
    <?php include('includes/js-files.php'); ?>
</body>
</html> 