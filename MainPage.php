<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>HOME PAGE | SmartCanteen</title>


  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet." href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- slidck slider -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha256-UK1EiopXIL+KVhfbFa8xrmAWPeBjMVdvYMYkTAEv/HI=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css.map" integrity="undefined" crossorigin="anonymous" />


  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
  }
  // Include your database connection here if needed
  include('php/config.php');

  // Fetch username from the database
  $email = $_SESSION['email'];
  $sql = "SELECT name FROM users WHERE email='$email'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $username = $row['name'];
  ?>

  <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="MainPage.php">
            <span class="headlog">
              SMART CANTEEN
            </span>
          </a>
          <div class="" id="">
            <div class="User_option">
              <a href="login.html">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span style="font-size: large; padding-right: 20px"><?php echo htmlspecialchars($username); ?></span>
              </a>
              <!-- <form class="form-inline ">
                <input type="search" placeholder="Search" />
                <button class="btn  nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </form> -->
            </div>
            <div class="custom_menu-btn">
              <button onclick="openNav()">
                <img src="images/menu.png" alt="">
              </button>
            </div>
            <div id="myNav" class="overlay">
              <div class="overlay-content">
                <a href="MainPage.php">Home</a>
                <a href="about.html">About</a>
                <a href="blog.html">Blog</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>

                <!-- <a href="testimonial.html">Testimonial</a> -->
              </div>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->

    <!-- slider section -->
    <section class="slider_section ">
      <div class="container ">
        <div class="row">
          <div class="col-lg-10 mx-auto">
            <div class="detail-box">
              <h1>
                Discover Restuarant And Food
              </h1>
              <p>
                -- an Online Food Ordering App --
              </p>
            </div>
            <!-- <div class="find_container ">
              <div class="container">
                <div class="row">
                  <div class="col">
                    <form>
                      <div class="form-row ">
                        <div class="form-group col-lg-5">
                          <input type="text" class="form-control" id="inputHotel" placeholder="Restaurant Name">
                        </div>
                        <div class="form-group col-lg-3">
                          <input type="text" class="form-control" id="inputLocation" placeholder="All Locations">
                          <span class="location_icon">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                          </span>
                        </div>
                        <div class="form-group col-lg-3">
                          <div class="btn-box">
                            <button type="submit" class="btn ">Search</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
      <div class="slider_container">
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img1.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img2.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img3.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img4.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img1.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img2.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img3.png" alt="" />
          </div>
        </div>
        <div class="item">
          <div class="img-box">
            <img src="images/slider-img4.png" alt="" />
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>


  <!-- recipe section -->

  <section class="recipe_section layout_padding-top">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Best Popular Restaurants
        </h2>
      </div>
      <div class="row">
        <div class="col-sm-6 col-md-4 mx-auto">
          <div class="box">
            <div class="img-box">
              <img src="images\SURF &TURF.jpeg" class="box-img" alt="Surf and Turf">
            </div>
            <div class="detail-box">
              <h4>
                Surf and Turf
              </h4>
              <a href="Surf&Turf.html">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-4 mx-auto">
          <div class="box">
            <div class="img-box">
              <img src="images\Cafe Mingos.jpeg" class="box-img" alt="Mingos">
            </div>
            <div class="detail-box">
              <h4>
                Cafe Mingos
              </h4>
              <a href="CafeMingos.html">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="btn-box">
        <a href="">
          Order Now
        </a>
      </div> -->
    </div>
  </section>

  <!-- end recipe section -->


  <section class="about_section layout_padding">
    <div class="container">
      <div class="col-md-11 col-lg-10 mx-auto">
        <div class="heading_container heading_center">
          <h2>
            About Us
          </h2>
        </div>
        <div class="box">
          <div class="col-md-7 mx-auto">
            <div class="img-box">
              <img src="images/about-img.jpg" class="box-img" alt="">
            </div>
          </div>
          <div class="detail-box">
            <!-- <p> -->
            SmartCanteen operates within the domains of Education and E-commerce, enhancing the food ordering experience in educational institutions. By leveraging e-commerce
            functionalities, the app provides a seamless online ordering process tailored to meet the specific needs of students and faculty, ensuring convenience and efficiency in campus
            life.SmartCanteen is meticulously crafted to cater specifically to the needs of educational institutions, with a primary focus on enhancing the food ordering experience for
            students within college campuses. The application encompasses a comprehensive range
            of functionalities designed to streamline every aspect of the canteen dining process.
            Central to its design is robust user authentication, ensuring secure access through
            college-provided email IDs, which not only safeguards sensitive information but also
            ensures exclusive access for verified users. Students will benefit from a user-friendly
            interface that allows them to browse through a curated list of canteen restaurants, each
            offering a diverse selection of meals and beverages. The platform enables seamless
            order placement, empowering students to customize their orders and make informed
            choices directly from their devices. Integration of secure online payment options further
            enhances convenience, allowing for hassle-free transactions without the need for
            physical currency.
            <!-- </p> -->
            <br><br>
            <center>
              <a href="">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
              </a>
            </center>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- news section -->

  <!-- <section class="news_section">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Latest News
        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="img-box">
              <img src="images/n1.jpg" class="box-img" alt="">
            </div>
            <div class="detail-box">
              <h4>
                Tasty Food For you
              </h4>
              <p>
                there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
              </p>
              <a href="">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="img-box">
              <img src="images/n2.jpg" class="box-img" alt="">
            </div>
            <div class="detail-box">
              <h4>
                Breakfast For you
              </h4>
              <p>
                there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
              </p>
              <a href="">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <!-- end news section -->

  <!-- client section -->

  <!-- <section class="client_section layout_padding">
    <div class="container">
      <div class="col-md-11 col-lg-10 mx-auto">
        <div class="heading_container heading_center">
          <h2>
            Testimonial
          </h2>
        </div>
        <div id="customCarousel1" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="detail-box">
                <h4>
                  SMART CANTEEN
                </h4>
                <p>
                  SmartCanteen operates within the domains of Education and E-commerce, enhancing
                  the food ordering experience in educational institutions. By leveraging e-commerce
                  functionalities, the app provides a seamless online ordering process tailored to meet the
                  specific needs of students and faculty, ensuring convenience and efficiency in campus
                  life.</p>
                <i class="fa fa-quote-left" aria-hidden="true"></i>
              </div>
            </div>
            <div class="carousel-item">
              <div class="detail-box">
                <h4>
                  SMART CANTEEN
                </h4>
                <p>
                  SmartCanteen operates within the domains of Education and E-commerce, enhancing
                  the food ordering experience in educational institutions. By leveraging e-commerce
                  functionalities, the app provides a seamless online ordering process tailored to meet the
                  specific needs of students and faculty, ensuring convenience and efficiency in campus
                  life.</p>
                <i class="fa fa-quote-left" aria-hidden="true"></i>
              </div>
            </div>
            <div class="carousel-item">
              <div class="detail-box">
                <h4>
                  SMART CANTEEN
                </h4>
                <p>
                  SmartCanteen operates within the domains of Education and E-commerce, enhancing
                  the food ordering experience in educational institutions. By leveraging e-commerce
                  functionalities, the app provides a seamless online ordering process tailored to meet the
                  specific needs of students and faculty, ensuring convenience and efficiency in campus
                  life.</p>
                <i class="fa fa-quote-left" aria-hidden="true"></i>
              </div>
            </div>
          </div>
          <a class="carousel-control-prev d-none" href="#customCarousel1" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section> -->

  <div>
    <link rel="stylesheet" href="css\chatbot.css">

    <body>
      <div id="chatbox">
        <div id="messages"></div>
        <div id="buttonContainer"></div>
        <input type="text" id="userInput" placeholder="Ask something...">
        <button id="sendButton">Send</button>
      </div>

      <!-- Circular button to toggle chatbot -->
      <button id="toggleChatButton">
        <img src="images\chef_hat.png" alt="Chef's Hat" style="width:50px;height:50px;">
      </button>

      <script src="js\chatbot.js"></script>\
    </body>
  </div>

  <!-- end client section -->

  <div class="footer_container">
    <!-- info section -->
    <section class="info_section ">
      <div class="container">
        <div class="contact_box">
          <a href="">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
          </a>
          <a href="">
            <i class="fa fa-phone" aria-hidden="true"></i>
          </a>
          <a href="">
            <i class="fa fa-envelope" aria-hidden="true"></i>
          </a>
        </div>
        <div class="info_links">
          <ul>
            <li class="active">
              <a href="MainPage.php">
                Home
              </a>
            </li>
            <li>
              <a href="about.html">
                About
              </a>
            </li>
            <!-- <li>
              <a class="" href="blog.html">
                Blog
              </a>
            </li> -->
            <!-- <li>
              <a class="" href="testimonial.html">
                Testimonial
              </a>
            </li> -->
          </ul>
        </div>
        <div class="social_box">
          <a href="https://christuniversity.in/">
            <i class="fa fa-facebook" aria-hidden="true"></i>
          </a>
          <a href="https://christuniversity.in/">
            <i class="fa fa-twitter" aria-hidden="true"></i>
          </a>
          <a href="https://christuniversity.in/">
            <i class="fa fa-linkedin" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>
    <!-- end info_section -->


    <!-- footer section -->
    <footer class="footer_section">
      <div class="container">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="https://html.design/">Chokasandra Boys</a><br>
          <!-- Distributed By: <a href="https://themewagon.com/">ThemeWagon</a> -->
        </p>
      </div>
    </footer>
    <!-- footer section -->

  </div>
  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- slick  slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>


</body>

</html>