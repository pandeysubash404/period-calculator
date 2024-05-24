<?php if (!isset($_GET["id"])) { ?>
	<script> history.back();</script>
	<?php
}
$id = $_GET['id'];

$title = 'Blog Category - Rakshya Medical';
$class = 'blog-category-page';
include_once ('header.php');

require_once '../db_config.php';
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>

<main id="main" style="margin-top: 100px;">
	<section id="blog-details" class="blog-details">

		<div class="container" data-aos="fade-up" data-aos-delay="100">

			<div class="row g-5">

				<div class="col-lg-8">
					<?php
					$sql = "SELECT * FROM blog LEFT JOIN categories ON blog.category=categories.cat_id LEFT JOIN author ON blog.author_id=author.author_id WHERE cat_id='$id' ORDER BY blog.publish_date DESC";
					$row = $conn->query($sql);

					if ($row->num_rows > 0) { ?>
						<!-- <h3 class="mb-0">Result for: <span class="text-primary"><?= $category["cat_name"] ?></span> category</h3> -->
						<hr />
						<?php
						while ($result = $row->fetch_assoc()) { ?>
							<article class="article">
								<div class="col-lg-12">
									<div class="card shadow">
										<div class="card-body d-flex blog_flex">
											<div class="flex-part1" style="margin-right:20px;">
												<a href="blog.php?id=<?= $result['blog_id'] ?>">
													<?php $img = $result['blog_image'] ?>
													<img src="./admin/upload/<?= $img ?>" style="width:100%;">
												</a>
											</div>
											<div class="flex-grow-1 flex-part2">
												<a href="single_post.php?id=<?= $result['blog_id'] ?>" id="title">
													<h5><?= ucfirst($result['blog_title']) ?></h5>
												</a>
												<p>
													<a href="single_post.php?id=<?= $result['blog_id'] ?>" id="body">
														<?= strip_tags(substr($result['blog_body'], 0, 150)) . "..." ?>
													</a> <span>
														<br /> <br />
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
														<a href="#"> <span><i class="fa fa-tag" aria-hidden="true"></i></span>
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
						echo "<h5 class='text-danger'>No Category Found</h5>
		    		<b>Suggestions:</b>
			    	<li>Make sure that words are spelled correctly.</li>
			    	<li>Try different keywords.</li>";
					}
					?>
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
// Close database connection
$conn->close();
include_once ('footer.php');
?>