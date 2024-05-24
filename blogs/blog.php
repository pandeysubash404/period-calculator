<!-- blog-details page -->
<?php if (!isset($_GET["id"])) { ?>
  <script> history.back();</script>
  <?php
}
$get_id = $_GET["id"];
$title = 'Blog Details - Rakshya Medical';
$class = 'blog-details-page';
$author_name = "";
include_once ('header.php');
?>


<main id="main" style="margin-top: 100px;">

  <!-- Blog Details Page Title & Breadcrumbs -->
  <div data-aos="fade" class="page-title">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1>Blog Details</h1>
            <p class="mb-0">A multispeciality hospital with expertise and compassionate patient care</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="../index.php">Home</a></li>
          <li class="current">Blog Details</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- Blog-details Section - Blog Details Page -->
  <section id="blog-details" class="blog-details">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-5">

        <div class="col-lg-8">
          <?php
          require_once '../db_config.php';

          // Create a new database connection
          $conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
          // Check the database connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $sql = "SELECT * FROM blog WHERE blog_id='$get_id'";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              ?>
              <article class="article">

                <div class="post-img">
                  <img src="./admin/upload/<?php echo $row["blog_image"]; ?>" alt="image not found" class="img-fluid">
                </div>

                <h2 class="title">
                  <?php echo $row["blog_title"] ?>
                </h2>

                <div class="meta-top">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="blog.php">
                        <?php
                        $author_id = $row['author_id'];
                        $sqls = "SELECT author_name FROM author WHERE author_id =$author_id";
                        $results = $conn->query($sqls);

                        // Check if any categories were found
                        if ($results->num_rows > 0) {
                          $author_row = $results->fetch_assoc();
                          $author_name = $author_row["author_name"];
                          echo $author_name;
                        }
                        ?>
                      </a></li>
                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="blog.php"><time
                          datetime="2024-01-01">
                          <?php echo $row["publish_date"] ?>
                        </time></a></li>
                  </ul>
                </div><!-- End meta top -->

                <div class="content">
                  <?php echo $row["blog_body"] ?>
                </div><!-- End post content -->

                <div class="meta-bottom">
                  <i class="bi bi-folder"></i>
                  <ul class="cats">
                    <?php
                    $category_id = $row['category'];
                    $sqls = "SELECT cat_name FROM categories WHERE cat_id =$category_id";
                    $results = $conn->query($sqls);

                    // Check if any categories were found
                    if ($results->num_rows > 0) {
                      $cat_row = $results->fetch_assoc();
                      ?>
                      <li>
                        <a href="category.php?id=<?= $category_id ?>">
                          <?php echo $cat_row["cat_name"]; ?>
                        </a>
                      </li>
                      <?php
                    }
                    ?>
                  </ul>
                </div><!-- End meta bottom -->

                <!-- End meta bottom -->

              </article><!-- End post article -->

              <div class="blog-author d-flex align-items-center">
                <img src="../assets/img/blog/blog-author.jpg" class="rounded-circle flex-shrink-0" alt="">
                <div>
                  <h4><?php echo $author_name; ?></h4>
                  <div class="social-links">
                    <a href="https://twitters.com/#"><i class="bi bi-twitter"></i></a>
                    <a href="https://facebook.com/#"><i class="bi bi-facebook"></i></a>
                    <a href="https://instagram.com/#"><i class="biu bi-instagram"></i></a>
                  </div>
                  <p>
                    Itaque quidem optio quia voluptatibus dolorem dolor. Modi eum sed possimus accusantium. Quas repellat
                    voluptatem officia numquam sint aspernatur voluptas. Esse et accusantium ut unde voluptas.
                  </p>
                </div>
              </div><!-- End post author -->
              <?php
            }
          } else { ?>
            <p class="justify-content-center text-center">No articles found</p>
          <?php } ?>
        </div>
        <div class="col-lg-4">
          <!-- Start Sidebar -->
          <?php include_once ("side-bars.php"); ?>
          <!-- End Sidebar -->
        </div>
      </div>

    </div>

  </section><!-- End Blog-details Section -->

</main>

<!-- footer -->
<?php
// Close database connection
$conn->close();
include_once ('footer.php');
?>