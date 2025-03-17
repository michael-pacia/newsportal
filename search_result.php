<?php
session_start();
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="includes/images/life2.png">
    <link rel="apple-touch-icon" href="includes/images/life2.png">
    <!-- App title -->
    <title>LifeRadio | Search Result</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* Enhanced responsive styles for search results page */
        .card {
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .card-text {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .card-body {
                padding: 15px;
            }

            .btn-primary {
                width: 100%;
                padding: 12px;
            }
        }

        /* Small mobile devices */
        @media (max-width: 480px) {
            .card-title {
                font-size: 1.2rem;
            }

            .card-text {
                font-size: 0.85rem;
            }
        }

        /* Medium devices (tablets) */
        @media (min-width: 481px) and (max-width: 768px) {
            .card-title {
                font-size: 1.4rem;
            }
        }

        /* Large devices */
        @media (min-width: 769px) {
            .container {
                max-width: 1140px;
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

        /* Print styles */
        @media print {
            .sidebar,
            .nav,
            .footer {
                display: none;
            }
        }

        /* Loading states */
        .card-img-top {
            transition: opacity 0.3s;
        }

        .card-img-top[loading] {
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="container">
        <div class="row" style="margin-top: 4%">
            <div class="col-md-8">
                <?php
                if(isset($_GET['searchBy']) && isset($_GET['query'])) {
                    $searchBy = $_GET['searchBy'];
                    $query = mysqli_real_escape_string($con, $_GET['query']);
                    
                    $sql = "";
                    switch($searchBy) {
                        case 'date':
                            $sql = "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, 
                                   tblposts.PostImage, tblposts.PostingDate as postingdate, 
                                   tblposts.PostUrl as url, tblposts.Author as author
                                   FROM tblposts 
                                   WHERE DATE(PostingDate) = DATE('$query') 
                                   AND tblposts.Is_Active = 1";
                            break;
                            
                        case 'author':
                            $sql = "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, 
                                   tblposts.PostImage, tblposts.PostingDate as postingdate, 
                                   tblposts.PostUrl as url, tblposts.Author as author
                                   FROM tblposts 
                                   WHERE Author = '$query' 
                                   AND tblposts.Is_Active = 1";
                            break;
                            
                        case 'subcategory':
                            $sql = "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, 
                                   tblposts.PostImage, tblposts.PostingDate as postingdate, 
                                   tblposts.PostUrl as url, tblposts.Author as author,
                                   tblsubcategory.Subcategory as subcategory
                                   FROM tblposts 
                                   LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
                                   WHERE tblsubcategory.Subcategory = '$query' 
                                   AND tblposts.Is_Active = 1";
                            break;
                    }

                    if($sql != "") {
                        $result = mysqli_query($con, $sql);
                        $rowcount = mysqli_num_rows($result);
                        
                        if($rowcount == 0) {
                            echo "<p>No results found</p>";
                        } else {
                            while($row = mysqli_fetch_array($result)) {
                ?>
                                <div class="card mb-4">
                                    <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>">
                                    <div class="card-body">
                                        <h2 class="card-title"><?php echo htmlentities($row['posttitle']);?></h2>
                                        <p class="card-text">
                                            <b>Posted on </b><?php echo htmlentities($row['postingdate']);?> 
                                            <b>By </b><?php echo htmlentities($row['author']);?>
                                        </p>
                                        <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="btn btn-primary">Read More &rarr;</a>
                                    </div>
                                </div>
                <?php
                            }
                        }
                    }
                } else {
                    echo "<p>Invalid search parameters</p>";
                }
                ?>
            </div>
            <?php include('includes/other_news.php');?>
        </div>
    </div>
    <?php include('includes/footer.php');?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>