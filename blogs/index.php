<!-- blog page -->
<?php
$title = 'Blog - Append Bootstrap Template';
$class = 'blog-page';
include_once ('header.php');
?>

<main id="main" style="margin-top: 100px;">

  <!-- Blog Page Title & Breadcrumbs -->
  <div data-aos="fade" class="page-title">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1>Blog</h1>
            <p class="mb-0">Busting menstrual myths to help improve health and combat social stigma.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="../index.php">Home</a></li>
          <li class="current">Blog</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- Blog Section - Blog Page -->
  <section id="blog" class="blog">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4 posts-list">

        <?php
        require_once '../db_config.php';

        // Create a new database connection
        $conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

        // Check the database connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch articles from the database
        $sql = " SELECT * FROM blog LEFT JOIN categories ON blog.category=categories.cat_id LEFT JOIN author ON blog.author_id=author.author_id ORDER BY blog.publish_date DESC";
        $result = $conn->query($sql);

        // Check if any articles were found
        if ($result->num_rows > 0) {
          // Output data of each row
          while ($row = $result->fetch_assoc()) {
            ?>

            <div class="col-xl-4 col-lg-6">
              <article>

                <div class="post-img">
                  <img src="./admin/upload/<?php echo $row["blog_image"]; ?>" alt="" class="img-fluid">
                </div>

                <p class="post-category">
                  <?php echo $row["cat_name"]; ?>
                </p>

                <h2 class="title">
                  <a href="blog.php?id=<?php echo $row["blog_id"]; ?>">
                    <?php echo $row["blog_title"]; ?>
                  </a>
                </h2>

                <div class="d-flex align-items-center">
                  <img src="../assets/img/blog/blog-author.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
                  <div class="post-meta">
                    <p class="post-author">
                      <?php echo $row["author_name"]; ?>
                    </p>
                    <p class="post-date">
                      <time datetime="2022-01-01">
                        <?php echo $row["publish_date"]; ?>
                      </time>
                    </p>
                  </div>
                </div>

              </article>
            </div><!-- End post list item -->

            <?php
          }
        } else {
          echo "No articles found";
        }
        // Close database connection
        $conn->close();
        ?>
      </div><!-- End blog posts list -->

      <div class="pagination d-flex justify-content-center">
        <ul>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
        </ul>
      </div><!-- End pagination -->

    </div>

  </section><!-- End Blog Section -->

</main>
<!-- footer -->
<?php
include_once ('footer.php');
?>