<!-- index page -->
<?php
$title = 'Home - Append Bootstrap Template';
$class = 'index-page';
include_once ('header.php');
?>

<main id="main">

  <!-- Hero Section - Home Page -->
  <section id="hero" class="hero">

    <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

    <div class="container">
      <div class="row">
        <div class="col-lg-10">
          <h2 data-aos="fade-up" data-aos-delay="100">Welcome to Period Calculator</h2>
          <p data-aos="fade-up" data-aos-delay="200">Live in sync with your cycle</p>
        </div>
        <div class="col-lg-5">
          <a href="dashboard/login.php">
            <button type="submit" class="btn">Add your cycle</button>
          </a>
        </div>
      </div>
    </div>

  </section><!-- End Hero Section -->

  <!-- Call-to-action Section - Home Page -->
  <section id="call-to-action" class="call-to-action" style="background-color: #FFD0DC;">

    <img src="assets/img/cta-bg.jpg" alt="">

    <div class="container">
      <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
        <div class="col-xl-10">
          <div class="text-center text-pink">
            <p>Wondering, "When will I get my period?" Our easy calculating tool, the Period Calculator, helps to map
              out
              your cycle for months. Plan a period-free beach trip or a big event like a wedding using the period cycle
              calculator.</p>
          </div>
        </div>
      </div>
    </div>

  </section><!-- End Call-to-action Section -->

  <style>
    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 2px;
      margin-top: 20px;
    }

    .calendar-data {
      text-align: center;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #fff;
    }

    .period {
      background-color: palevioletred;
      color: white;
    }

    .ovulation {
      background-color: teal;
    }

    .month-title {
      text-align: center;
      background-color: #ccc;
      padding: 5px;
    }
  </style>


  <!-- Calculator Section - Home Page -->
  <section id="calculator" class="about">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="section-title text-center">
        <h3>Need to plan a vacation but not sure of your monthly cycle? Calculate your periods!</h3>
      </div>
      <form id="period-form">
        <!-- Start -->
        <div class=" d-flex align-items-center justify-content-between small row row-cols-3 text-center mt-3">
          <div class="col">
            <h5 class="mb-2 fw-normal">Date of your last period?</h5>
            <input type="date" class="form-control form-control-sm form-pink" id="last-period-start" max="" required
              value="">
          </div>
          <div class="col">
            <h5 class="mb-2 fw-normal">How long did it last?</h5>
            <div class="input-group">
              <button class="btn btn-pink" id="decrementDuration">-</button>
              <input type="text" class="form-control form-control-sm" value="0" readonly id="period-duration" min="1"
                max="10" required>
              <button class="btn btn-pink" id="incrementDuration">+</button>
            </div>
          </div>
          <div class="col">
            <h5 class="mb-2 fw-normal">What is your usual cycle length?</h5>
            <div class="input-group">
              <button class="btn btn-pink" id="decrementCycle">-</button>
              <input type="number" class="form-control form-control-sm" value="0" id="cycle-length" min="18" max="40"
                required>
              <button class="btn btn-pink" id="incrementCycle">+</button>
            </div>
          </div>
        </div>
        <div class="mt-5 align-items-center text-center">
          <button type="submit" id="calculateCycleBtn" class="btn btn-pink btn-lg px-4">Calculate your cycle</button>
        </div>
      </form>
      <!-- End -->

      <!-- Calendar section -->
      <!-- Purple shows- Period days, Green shows- Ovulation days -->
      <div id="calendarSection" class="row mt-5">
        <div class="col">
          <div class="row">
            <div class="col">
              <!-- Shows selected month (e.g. Jan)-->
              <div id="calendar1" class="calendar"></div>
            </div>
            <div class="col">
              <!-- Shows selected next month (e.g. Feb)-->
              <div id="calendar2" class="calendar"></div>
            </div>
            <div class="col">
              <!-- Shows selected the next next month (e.g. Mar)-->
              <div id="calendar3" class="calendar"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Calculate Three Months Period JS File -->
      <script>
        var today = new Date();
        var minDate = new Date(today.getFullYear(), today.getMonth() - 2, 1).toISOString().split('T')[0];
        document.getElementById('last-period-start').setAttribute('min', minDate);
        document.getElementById('last-period-start').setAttribute('max', new Date().toISOString().split('T')[0]);

        var prevEnd;

        document.getElementById('period-form').addEventListener('submit', function (event) {
          event.preventDefault();

          var lastPeriodStart = document.getElementById('last-period-start').value;
          var periodDuration = parseInt(document.getElementById('period-duration').value);
          var cycleLength = parseInt(document.getElementById('cycle-length').value);

          calculateAndHighlightCycle(lastPeriodStart, periodDuration, cycleLength);
        });

        function calculateAndHighlightCycle(lastPeriodStart, periodDuration, cycleLength) {
          var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
          var calendar1 = document.getElementById('calendar1');
          var calendar2 = document.getElementById('calendar2');
          var calendar3 = document.getElementById('calendar3');
          calendar1.innerHTML = '';
          calendar2.innerHTML = '';
          calendar3.innerHTML = '';

          var periodStartDate = new Date(lastPeriodStart);
          var calendarCount = 1;

          for (var i = 1; i < periodStartDate.getDate(); i++) {
            var calendarCell = document.createElement('div');
            calendarCell.classList.add('calendar-data');
            calendarCell.innerHTML = i;

            targetCalendar = calendar1;

            targetCalendar.appendChild(calendarCell);
          }

          for (var i = 0; i < 365; i++) {
            var currentDate = new Date(periodStartDate.getTime() + (24 * 60 * 60 * 1000 * i));

            var monthInfo = getMonthStartAndEndDate(currentDate.getFullYear(), currentDate.getMonth() + 1);

            if (currentDate.getDate() == 1) {
              calendarCount++;
              if (calendarCount == 2) {
                targetCalendar = calendar2;
              } else {
                targetCalendar = calendar3;
              }
            }
            // Create calendar cell
            var calendarCell = document.createElement('div');
            calendarCell.classList.add('calendar-data');
            calendarCell.innerHTML = currentDate.getDate();

            // Add appropriate classes based on period and ovulation
            if (i % cycleLength < periodDuration) {
              calendarCell.classList.add('period');
            } else if (i % cycleLength >= cycleLength - 14 && i % cycleLength < cycleLength - 9) {
              calendarCell.classList.add('ovulation');
            }

            // Append calendar cell to the target calendar
            targetCalendar.appendChild(calendarCell);
            if (currentDate > new Date(periodStartDate.getFullYear(), periodStartDate.getMonth() + 3, 0)) {
              break;
            }
          }
        }

        function getMonthStartAndEndDate(year, month) {
          var firstDayOfMonth = new Date(year, month - 1, 1);
          var lastDayOfMonth = new Date(year, month, 0);
          var startDay = firstDayOfMonth.getDate();
          var endDay = lastDayOfMonth.getDate();

          console.log("Month:" + month);
          console.log("Start Day:" + startDay);
          console.log("Last Day:" + lastDayOfMonth.getDate());

          return {
            startDay: startDay,
            endDate: endDay
          };
        }

        // Increment and decrement duration value 
        var durationValue = document.getElementById('period-duration');
        document.getElementById('incrementDuration').addEventListener('click', function () {
          if ((parseInt(durationValue.value) >= 0) && (parseInt(durationValue.value) <= 10)) {
            durationValue.value = parseInt(durationValue.value) + 1;
          } else {
            durationValue.value = 0;
          }
        });

        document.getElementById('decrementDuration').addEventListener('click', function () {
          if (parseInt(durationValue.value) > 0) {
            durationValue.value = parseInt(durationValue.value) - 1;
          }
        });

        // Increment and decrement cycle length value 
        var cycleLengthValue = document.getElementById('cycle-length');
        document.getElementById('incrementCycle').addEventListener('click', function () {
          cycleLengthValue.value = parseInt(cycleLengthValue.value) + 1;
        });
        document.getElementById('decrementCycle').addEventListener('click', function () {
          if (parseInt(cycleLengthValue.value) > 0) {
            cycleLengthValue.value = parseInt(cycleLengthValue.value) - 1;
          }
        });
      </script>
    </div>

  </section>
  <!-- End About Section -->



  <!-- Features Section - Home Page -->
  <section id="features" class="features">
    <div class="container">
      <div class="row gy-4 align-items-stretch justify-content-between features-item ">
        <div class="col-lg-6 d-flex align-items-center features-img-bg" data-aos="zoom-out">
          <img src="assets/img/features-light-3.jpg" class="img-fluid" alt="">
        </div>
        <div class="col-lg-5 d-flex justify-content-center flex-column" data-aos="fade-up">
          <h3>Get health advice from our medical experts</h3>
          <p>Benefit from tips and blogs about periods, sex and reproductive health, complete with medical and
            scientific references.</p>
          <a href="blogs/" class="btn btn-get-started align-self-start">Read Blogs</a>
        </div>
      </div><!-- Features Item -->

    </div>

  </section><!-- End Features Section -->



  <!-- Faq Section - Home Page -->
  <section id="faq" class="faq">

    <div class="container">

      <div class="row gy-4">

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="content px-xl-5">
            <h3>Know more about how to use the <strong>Period Calculator</strong> check out our FAQs:
            </h3>
          </div>
        </div>

        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

          <div class="faq-container">
            <div class="faq-item faq-active">
              <h3><span class="num">1.</span> <span>What is a period calculator?</span></h3>
              <div class="faq-content">
                <p>Always be prepared for your upcoming periods and know in advance when you’ll be ovulating thanks to
                  the effortless Period Calculator.</p>
                <br>
                <p> Never be surprised when it’s that time of the month as Period Calculator ensures you know exactly
                  when to expect your next period.</p>
                <br>
                <p> Keeping monthly calculations of your period and other symptoms help you recognize when you’re
                  ovulating and anticipate your period so you can feel fresh and prepared every day of your menstrual
                  cycle.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3><span class="num">2.</span> <span>How to use our period calculator?</span></h3>
              <div class="faq-content">
                <p>Simply answer the following 3 questions to get started:</p>
                <br>
                <ul>
                  <li>When did your last period start?</li>
                  <li>How long did your period last?</li>
                  <li>How long is your menstrual cycle?</li>
                </ul>
                <p>Now, click on the “Track it“ button and you are all set!</p>
                <br>
                <p>Period Calculator will inform you when is your next period due and also provide info regarding useful
                  information about products that you might like.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3><span class="num">3.</span> <span>How the calculator calculates the menstrual cycle?</span></h3>
              <div class="faq-content">
                <p><strong>Period Calculator</strong> predicts the time to expect your next period by analyzing your
                  period history data.
                  Every girl and every cycle is unique so it's important to remember that this the best estimate of your
                  menstrual cycle. Better to feel prepared than surprised, no?</p>

                <p>The average period lasts up to 28 days, but it’s completely normal to have a slightly longer or
                  shorter cycle. No need to worry as it’s all very natural. Considering that, period calculator is not
                  <strong>100%</strong> accurate for girls who have <strong>irregular periods</strong>, as its extremely
                  complicated to predict the date
                  of the next period in those cases, however, it can help give you an overview of just how irregular
                  your period is and therefore have more of an idea of what to expect in future.
                </p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

          </div>

        </div>
      </div>

    </div>

  </section><!-- End Faq Section -->


  <!-- Call-to-action Section - Home Page -->
  <section id="call-to-action" class="call-to-action">

    <img src="assets/img/cta-bg.jpg" alt="">

  </section><!-- End Call-to-action Section -->


  <!-- Recent-posts Section - Home Page -->
  <section id="recent-posts" class="recent-posts">

    <!--  Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Recent Posts</h2>
      <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
    </div><!-- End Section Title -->

    <div class="container">

      <div class="row gy-4">

        <!-- Recent -->
        <?php
        require_once 'db_config.php';

        // Create a new database connection
        $conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

        // Check the database connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch articles from the database
        
        $sql = "  SELECT * FROM blog LEFT JOIN categories ON blog.category=categories.cat_id LEFT JOIN author ON blog.author_id=author.author_id ORDER BY blog.publish_date DESC LIMIT 3";
        // $sql = "SELECT p.id, p.date,p.images, c.name AS category_name, p.title, p.author FROM post p JOIN category c ON p.category_id = c.id";
        $result = $conn->query($sql);

        // Check if any articles were found
        if ($result->num_rows > 0) {
          // Output data of each row
          while ($row = $result->fetch_assoc()) {
            ?>

            <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <article>

                <div class="post-img">
                  <img src="blogs/admin/upload/<?php echo $row["blog_image"]; ?>" alt="" class="img-fluid">
                </div>

                <p class="post-category">
                  <?php echo $row["cat_name"]; ?>
                </p>

                <h2 class="title">
                  <a href="blogs/blog.php?id=<?php echo $row["blog_id"]; ?>">
                    <?php echo $row["blog_title"]; ?>
                  </a>
                </h2>

                <div class="d-flex align-items-center">
                  <img src="assets/img/blog/blog-author.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
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

      </div><!-- End recent posts list -->

    </div>

  </section><!-- End Recent-posts Section -->

  <!-- Contact Section - Home Page -->
  <section id="contact" class="contact">

    <!--  Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p>Online customer support is available.</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4">

        <div class="col-lg-6">

          <div class="row gy-4">
            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="200">
                <i class="bi bi-geo-alt"></i>
                <h3>Address</h3>
                <p>Abc</p>
                <p>Butwal, Nepal</p>
              </div>
            </div><!-- End Info Item -->

            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="300">
                <i class="bi bi-telephone"></i>
                <h3>Call Us</h3>
                <p>+071-543456</p>
                <p>+977-9812345670</p>
              </div>
            </div><!-- End Info Item -->

            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="400">
                <i class="bi bi-envelope"></i>
                <h3>Email Us</h3>
                <p>pandeysubash404@gmail.com</p>
              </div>
            </div><!-- End Info Item -->

            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="500">
                <i class="bi bi-clock"></i>
                <h3>Open Hours</h3>
                <p>Sunday - Friday</p>
                <p>7:00AM - 06:00PM</p>
              </div>
            </div><!-- End Info Item -->

          </div>

        </div>

        <div class="col-lg-6">
          <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">

              <div class="col-md-6">
                <input type="text" name="name" class="" placeholder="Your Name" required>
              </div>

              <div class="col-md-6 ">
                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
              </div>

              <div class="col-md-12">
                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
              </div>

              <div class="col-md-12">
                <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
              </div>

              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>

                <button type="submit">Send Message</button>
              </div>

            </div>
          </form>
        </div><!-- End Contact Form -->

      </div>

    </div>

  </section><!-- End Contact Section -->

</main>

<!-- footer -->
<?php
include_once ('footer.php');
?>