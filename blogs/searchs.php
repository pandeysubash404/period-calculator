<?php if (!isset($_GET["keyword"])) { ?>
    <script> history.back();</script>
    <?php
}
$keyword = $_GET["keyword"];
// pagination
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$limit = 5;
$offset = ($page - 1) * $limit;

$title = 'Blog Search - Append Bootstrap Template';
$class = 'blog-search-page';
include_once ('header.php');

require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

echo "Database connected";
// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<main id="main" style="margin-top: 100px;">
    <section id="blog-details" class="blog-details">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row g-5">

                <div class="col-lg-8">
                    <h3 class="mb-0">Search result for: <span class="text-primary"><?= $keyword ?></span></h3>

                    <?php
                    $sql = "SELECT * FROM blog LEFT JOIN categories ON blog.category=categories.cat_id LEFT JOIN author ON blog.author_id=author.author_id WHERE blog_title like '%$keyword%' or blog_body like '%$keyword%' ORDER BY blog.publish_date DESC limit $offset,$limit";
                    $results = $conn->query($sql);

                    if ($results->num_rows > 0) {
                        while ($result = $results->fetch_assoc()) {
                            ?>
                            <article class="article">
                                <div class="col-lg-12">
                                    <hr />
                                    <div class="card shadow">
                                        <div class="card-body d-flex blog_flex">
                                            <div class="flex-part1" style="margin-right:20px;">
                                                <a href="single_post.php?id=<?= $result['blog_id'] ?>">
                                                    <?php $img = $result['blog_image'] ?>
                                                    <img src="./admin/upload/<?= $img ?>" style="width:100%;">
                                                </a>
                                            </div>
                                            <div class="flex-grow-1 flex-part2">
                                                <a href="single_post.php?id=<?= $result['blog_id'] ?>" id="title">
                                                    <h5><?= ucfirst($result['blog_title']) ?></h5>
                                                </a>
                                                <p>
                                                    <a href="blog.php?id=<?= $result['blog_id'] ?>" id="body">
                                                        <?= strip_tags(substr($result['blog_body'], 0, 200)) . "..." ?>
                                                    </a> <span>
                                                        <br><br>
                                                        <a href="blog.php?id=<?= $result['blog_id'] ?>"
                                                            class="btn btn-sm btn-outline-danger">Continue Reading
                                                        </a></span>
                                                </p>
                                                <ul>
                                                    <li class="me-2"><a href=""> <span><i class="fa fa-pencil-square-o"
                                                                    aria-hidden="true"></i></span>
                                                            <?= $result['author_name'] ?> </a>
                                                    </li>
                                                    <li class="me-2">
                                                        <a href=""> <span><i class="fa fa-calendar-o"
                                                                    aria-hidden="true"></i></span>
                                                            <?php $date = $result['publish_date'] ?>
                                                            <?= date('d-M-Y', strtotime($date)) ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="category.php?id=<?= $result['cat_id'] ?>">
                                                            <span><i class="fa fa-tag" aria-hidden="true"></i></span>
                                                            <?= $result['cat_name'] ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php }
                    } else {
                        echo "<h5 class='text-danger'>No Record Found</h5>
		    	<b>Suggestions:</b>
			    <li>Make sure that words are spelled correctly.</li>
			    <li>Try different keywords.</li>";
                    } ?>
                    <!-- Pagination begin -->
                    <?php
                    $pagination = "SELECT * FROM blog WHERE blog_title like '%$keyword%' or blog_body like '%$keyword%'";
                    $run_q = $conn->query($pagination);
                    $total_post = mysqli_num_rows($run_q);
                    $pages = ceil($total_post / $limit);
                    if ($total_post > $limit) {
                        ?>
                        <article class="article">
                            <ul class="pagination pt-2 pb-5">
                                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? $active = "active" : ""; ?>">
                                        <a href="search.php?keyword=<?= $keyword ?>&page=<?= $i ?>" class="page-link">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </article>
                    <?php } ?>
                    <!-- ------------------------- -->
                </div>
                <div class="col-lg-4">
                    <!-- Start Sidebar -->
                    <?php include_once ("side-bars.php"); ?>
                    <!-- End Sidebar -->
                </div>
            </div>
        </div>
    </section>
</main>
<!-- footer -->
<?php
include_once ('footer.php');
?>