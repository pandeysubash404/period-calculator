<div class="sidebar">
    <?php
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = "";
    }
    ?>
    <div class="sidebar-item search-form">
        <h3 class="sidebar-title">Search</h3>
        <form action="searchs.php" method="GET" class="mt-3">
            <input type="text" name="keyword" required maxlength="70" autocomplete="off" value="<?= $keyword ?>">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End sidebar search formn-->

    <div class="sidebar-item categories">
        <h3 class="sidebar-title">Categories</h3>
        <ul class="mt-3">

            <?php
            $sqls = "SELECT * FROM categories";
            $results = $conn->query($sqls);

            // Check if any categories were found
            if ($results->num_rows > 0) {
                //   $cat_row = $results->fetch_assoc();
                while ($cat_row = $results->fetch_assoc()) { ?>
                    <li>
                        <a href="./category.php?id=<?= $cat_row['cat_id'] ?>">
                            <?php echo $cat_row["cat_name"]; ?> <span>(25)</span>
                        </a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div><!-- End sidebar categories-->

    <div class="sidebar-item recent-posts">
        <h3 class="sidebar-title">Recent Posts</h3>
        <?php
        $sqls = "SELECT blog_id, blog_title, publish_date, blog_image FROM blog ORDER BY publish_date DESC limit 5";
        $results = $conn->query($sqls);

        // Check if any categories were found
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                ?>
                <div class="post-item">
                    <img src="./admin/upload/<?php echo $row["blog_image"]; ?>" alt="" class="flex-shrink-0">
                    <div>
                        <h4><a href="blog.php?id=<?= $row['blog_id'] ?>">
                                <?php echo $row["blog_title"]; ?>
                            </a></h4>
                        <time datetime="2020-01-01">
                            <?php echo $row["publish_date"]; ?>
                        </time>
                    </div>
                </div>
                <?php
            }
        }
        ?>

    </div><!-- End sidebar recent posts-->

</div>