<?php
    // Include database connection
    include_once './barber/config/dbConnection.php';

    // Fetch contact details
    $query = "SELECT * FROM contact_us";
    $result = mysqli_query($conn, $query);
    $contactDetails = mysqli_fetch_array($result);

    // Counter for serial number
    $serialNumber = 1;
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Tags for SEO -->
    <meta name="title" content="BarberBook: Seamless Barbershop Appointment Booking System" />
    <meta name="description" content="BarberBook provides a seamless solution for scheduling your next haircut appointment. Our user-friendly platform connects you with talented & skilled barbers, ensuring effortless and convenient booking." />
    <meta name="keywords" content="online barber booking, barbershop scheduling software, barber appointment app, haircut reservation system, salon appointment software, barber booking app, appointment scheduling for barbers, barber appointment booking software">

    <!-- Favicon Icon -->
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-1.png" sizes="32x32"> -->
    <link rel="icon" type="image/x-icon" href="./favicons/favicon-2.png" sizes="32x32">
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-3.png"> -->

    <!-- Include Remixicon font styles from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <title>BarberBook: Barbershop Appointment Booking System</title>

    <!-- Style -->
    <link rel="stylesheet" href="./components/button.css">
    <link rel="stylesheet" href="./css/Style.css">
    <link rel="stylesheet" href="./css/service.css">
    <link rel="stylesheet" href="./css/Contact.css">
    <link rel="stylesheet" href="./css/Footer.css">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- hugeicons -->
    <link rel="stylesheet" href="assets/hugeicons/hugeicons-font.css">
    
</head>
<body>
    <!-- Cursor -->
    <div class="cursor"></div>
   
    <!-- === Header === -->

    <header class="header" id="header">
        <nav class="nav container">
            <a class="nav__logo">
                <img src="./icons/logo.png" alt="Logo" class="cursor-scale small">BarberBook
            </a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link cursor-scale small">HOME</a>
                    </li>
                    <li class="nav__item">
                        <a href="#service" class="nav__link cursor-scale small">SERVICE LIST</a>
                    </li>
                    <li class="nav__item">
                        <a href="#contact" class="nav__link cursor-scale small">CONTACT</a>
                    </li>
                    <li class="nav__item">
                        <a href="./barber/index.php" class="nav__link cursor-scale small">BARBER</a>
                    </li>
                    <button class="button cursor-scale">
                        <span>BOOK APPOINTMENT</span>
                    </button>
                </ul>
                <!-- Close Button -->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            <div class="nav__actions">
                <!-- Toggle button -->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
        </nav>
    </header>
    <!-- === Main === -->

    <main class="main">

        <!-- Home -->

        <section class="home section" id="home">
            <div class="home__container container grid">
               <div class="image_container">
                 <img id="homeImage" src="./images/barbershop-amico.png" alt="image" class="home__img bounce cursor-scale">
               </div>
                <div class="home__data">
                    <h1 class="home__title cursor-scale">
                        MAKE YOUR <br>
                        OWN <span>STYLE</span>
                    </h1>
                    <p class="home__description cursor-scale small">
                    BarberBook provides a seamless solution for scheduling your next haircut appointment. Our user-friendly platform connects you with talented & skilled barbers, ensuring effortless and convenient booking.
                    </p>
                    <div class="home__button">
                        <a href="./appointment/appointment.php" class="buttonBook">
                            <span>
                                <i class="ri-arrow-right-line"></i>
                            </span>
                            MAKE AN APPOINTMENT
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service List -->

        <!-- <section class="service section container" id="service">
            <h2 style="font-size: 40px;">
                Services
            </h2>
            <div class="service__container grid">
                <div class="display">
                    <table class="display-table">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Service Name</th>
                                <th>Service Price</th>
                                <th>Service Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Check if there are any services
                                if($result -> num_rows > 0)
                                {
                                    // Output data of each row
                                    while($row = $result -> fetch_assoc()){
                                
                            ?>
                            <tr>
                                <td data-label="S.N."><?php echo $serialNumber++; ?></td>
                                <td data-label="Service Name"><?php echo $row['service_name']; ?></td>
                                <td data-label="Service Price"><?php echo $row['cost']; ?></td>
                                <td data-label="Service Description"><?php echo $row['description']; ?></td>
                            </tr>
                            <?php
                                    }
                                }else{
                                    // If there are no services
                                ?>
                                <tr><td colspan="4">No services found</td></tr>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section> -->
        <section class="container service section " id="service">
    <h1 class="container__title cursor-scale small">Services💈✂️</h1>
    <div class="card__container">
        <?php
        // Fetch service list from the database
        $fetchQuery = "SELECT * FROM services";
        $result = $conn->query($fetchQuery);

        // Check if there are any services
        if ($result->num_rows > 0) {
            // Loop through each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <article>
                    <!-- CARD PRODUCT -->
                    <div class="card__product">
                        <!-- Assuming you have an image URL in your database -->
                        <img src="./barber/src/uploaded_img/<?= $row['image'] ?>?<?= time() ?>" alt="Service Image" class="card__img">

                        <div>
                            <h3 class="card__name"><?php echo $row['service_name']; ?></h3>
                            <span class="card__price"><?php echo $row['cost']; ?></span>
                        </div>
                    </div>

                    <!-- POPUP MODAL -->
                    <div class="modal">
                        <div class="modal__card">
                            <i class="ri-close-large-line modal__close"></i>

                            <img  src="./barber/src/uploaded_img/<?= $row['image'] ?>?<?= time() ?>" alt="Service Image" class="modal__img">

                            <div>
                                <h3 class="modal__name"><?php echo $row['service_name']; ?></h3>
                                <p class="modal__info">
                                    <?php echo $row['description']; ?>
                                </p>
                                <span class="modal__price"><?php echo $row['cost']; ?></span>
                            </div>

                            <div class="modal__buttons">
                                <a href="./appointment/appointment.php">
                                   <button class="modal__button">BOOK APPOINTMENT</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            }
        } else {
            // If there are no services
            echo '<p>No services found.</p>';
        }
        ?>
    </div>
</section>
        <!-- Contact -->
        <section class="contact section container" id="contact">
            <h1 class="cursor-scale small">Contact Us ✉️</h1>
            <div class="contact__container grid">
                <div class="container_wrapper">
                    <div class="left">
                        <h3 class="heading">Get In Touch</h3>
                        <div class="text">
                            <marquee behavior="" direction="">
                            We are here for you! How can we help?
                            </marquee>
                        </div>
                        
                        <form action="https://api.web3forms.com/submit" method="Post">
                            <input type="hidden" name="access_key" value="b04535a3-865e-47aa-b7b8-e66e18ccd2fc">
                            <input type="hidden" name="subject" value="Barbershop Booking System: New Contact Page Message">
                            <div class="input-box">
                                <input type="text" name="name" class="name" placeholder="Enter name" autocomplete="off">
                            </div>
                            <div class="input-box">
                                <input type="email" name="email" class="email" placeholder="Enter email">
                            </div>
                            <div class="input-box">
                                <textarea name="message" class="message" placeholder="Enter message..."></textarea>
                            </div>
                            <button type="submit" class="btn">Send</button>
                        </form>
                    </div>
                    <div class="right">
                        <div class="image cursor-scale ">
                            <img src="./images/contact.svg" alt="Contact Us">
                        </div>
                        <div class="contact_info">
                            <div class="infoBox">
                                <div class="icon">
                                    <i class='bx bxs-envelope' id="email"></i>
                                </div>
                                <div class="text">
                                    <?php echo $contactDetails['email']; ?>
                                </div>
                            </div>
                            <div class="infoBox">
                                <div class="icon">
                                    <i class='bx bxs-phone'></i>
                                </div>
                                <div class="text">
                                    <?php echo $contactDetails['mobile_number']; ?>
                                </div>
                            </div>
                            <div class="infoBox">
                                <div class="icon">
                                    <i class='bx bxs-time'></i>
                                </div>
                                <div class="text">
                                    <?php echo $contactDetails['timing']; ?>
                                </div>
                            </div>
                            <div class="infoBox">
                                <div class="icon">
                                    <i class='bx bxs-location-plus' id="address"></i>
                                </div>
                                <div class="text">
                                    <?php echo $contactDetails['address']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="social-icons">
                        <a href="#">
                            <i class='bx bxl-facebook-circle'></i>
                        </a>
                        <a href="#">
                            <i class='bx bxl-instagram-alt'></i>
                        </a>
                        <a href="#">
                            <i class='bx bxl-snapchat'></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        

        <!-- Admin -->

    </main>

    <!-- === Footer === -->
    <footer>
        <div class="footer-content ">
            <h3 class="cursor-scale small">Barbershop Booking System</h3>
            <p class="cursor-scale small">Barbershop Booking System provides a seamless solution for scheduling your next haircut appointment. Our user-friendly platform connects you with talented & skilled barbers, ensuring effortless and convenient booking.</p>
            <ul class="socials">
                <li><i class='bx bxl-facebook-circle cursor-scale small'></i></li>
                <li><i class='bx bxl-instagram-alt cursor-scale small'></i></li>
                <li><i class='bx bxl-snapchat cursor-scale small'></i></li>
            </ul>
            <div class="copyright">
                <p>&copy; 2024 BarberBook. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Script -->
    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script src="./js/animate.js" type="text/javascript"></script>
    <script src="./js/main.js" type="text/javascript"></script>
    <script>
        // Function to check if an element is in view
        function isInViewport(elem) {
            const bounding = elem.getBoundingClientRect();
            return (
                bounding.top >= 0 &&
                bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight)
            );
        }
        // Function to set active class to the appropriate nav link
        function setActiveNavLink() {
            const sections = document.querySelectorAll('section');
            sections.forEach(section => {
                const navLink = document.querySelector(`.nav__link[href="#${section.id}"]`);
                if (isInViewport(section)) {
                    navLink.classList.add('active');
                } else {
                    navLink.classList.remove('active');
                }
            });
        }

        // Add event listener for scrolling
        window.addEventListener('scroll', setActiveNavLink);

        // Call setActiveNavLink initially to set the active link when the page loads
        setActiveNavLink();

        // Color change
        document.addEventListener("DOMContentLoaded", function() {
            let span = document.querySelector(".home__title span");

            setInterval(function() {
                let randomColor = getRandomColor(); // Generate a random color
                span.style.color = randomColor; // Apply the random color
            }, 3000); // Change color every 4 seconds

            function getRandomColor() {
                let letters = '0123456789ABCDEF';
                let color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        });

        // Redirect
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('email').addEventListener('click', function () {
                // Redirect to Gmail with the email address
                var email = '<?php echo $contactDetails['email']; ?>';
                window.open('https://mail.google.com/mail/?view=cm&fs=1&to=' + encodeURIComponent(email));
            });

            document.getElementById('address').addEventListener('click', function () {
                // Open Google Maps with the address
                var address = '<?php echo $contactDetails['address']; ?>';
                window.open('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(address));
            });
        });

    </script>

    <script src="./js/contact.js"></script>
    <script src="./js/card.js"></script>
</body>
</html>