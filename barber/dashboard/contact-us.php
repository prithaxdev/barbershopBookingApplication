<?php
    // Include database connection
    include_once '../config/dbConnection.php';

    require_once('../dashboard/logout.php'); // Include the logout file

    // Fetch contact details
    $query = "SELECT * FROM contact_us";
    $result = mysqli_query($conn, $query);
    $contactDetails = mysqli_fetch_array($result);

    // Check if form is submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve and sanitize form data
        $id = $contactDetails['id']; // assuming you have an 'id' column
        $page_title = mysqli_real_escape_string($conn, $_POST['page_title']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
        $timing = mysqli_real_escape_string($conn, $_POST['timing']);
        $address = mysqli_real_escape_string($conn, $_POST['address']); // updated field name

        // Prepare and bind parameters
        $query = "UPDATE contact_us SET page_title=?, email=?, mobile_number=?, timing=?, address=? WHERE id=?"; // updated field name
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', $page_title, $email, $mobile_number, $timing, $address, $id); // updated field name

        // Execute query
        if(mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contact details updated successfully!')</script>";
        } else {
            echo "<script>alert('Error updating contact details.')</script>";
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Update Contact Details</title>

    <!-- Style -->
    <link rel="stylesheet" href="../src/css/Style.css">
    <link rel="stylesheet" href="../src/css/Contact.css">
    <style>
      /* Cursor */
      .cursor {
        position: fixed;
        width: 40px;
        height: 40px;
        margin-left: -20px;
        border-radius: 50%;
        border: 2px solid #16a085;
        transition: transform 0.2s ease;
        transform-origin: center center;
        pointer-events: none;
        z-index: 1000;
        }
        .grow,
        .grow-small {
        transform: scale(7);
        background-color: #fff;
        mix-blend-mode: difference;
        border: none;
        }
        .grow-small {
        transform: scale(2.5);
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

     <!-- Favicon Icon -->
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-1.png" sizes="32x32"> -->
    <link rel="icon" type="image/x-icon" href="../favicon/favicon-2.png" sizes="32x32">
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-3.png"> -->
</head>
<body>
  <div class="cursor"></div>
<div class="sidebar">
      <div class="logo"></div>
      <ul class="menu">
        <li>
          <a href="./dashboard.php">
            <!-- Link to Dashboard -->
            <i class="fa-solid fa-gauge-high"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="./services.php">
            <i class="fa-solid fa-toolbox"></i>
            <span>Services</span>
          </a>
        </li>
        <li>
          <a href="./appointment.php">
            <!-- Link to Appointment page -->
            <i class="fa-solid fa-calendar-days"></i>
            <span>Appointment</span>
          </a>
        </li>
        <li class="active">
          <a href="#">
            <!-- Link to Contact page -->
            <i class="fa-solid fa-envelope"></i>
            <span>Contact</span>
          </a>
        </li>
        <!-- <li class="logout">
          <a href="#">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
          </a>
        </li> -->
        <li class="logout">
          <form id="logoutForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <input type="hidden" name="logout" value="true">
              <a href="#" onclick="document.getElementById('logoutForm').submit();">
                  <i class="fa-solid fa-right-from-bracket"></i>
                  <span>Logout</span>
              </a>
          </form>
        </li>
      </ul>
    </div>
    <div class="main--content">
        <div class="header-wrapper">
        <div class="header-title">
            <h2 class="cursor-scale small">Contact</h2>
        </div>
    </div>
    <main class="main--content">
        <div class="container">
            <div class="header-title">Update Contact Details</div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="contact-details">
                    <div class="input-box">
                        <span class="details">Page Title:</span>
                        <input type="text" id="page_title" name="page_title" value="<?php echo $contactDetails['page_title']; ?>">
                    </div>
                    <div class="input-box">
                        <span class="details">Email:</span>
                        <input type="email" id="email" name="email" value="<?php echo $contactDetails['email']; ?>">
                    </div>
                    <div class="input-box">
                        <span class="details">Mobile Number:</span>
                        <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $contactDetails['mobile_number']; ?>">
                    </div>
                    <div class="input-box">
                        <span class="details">Timing:</span>
                        <input type="text" id="timing" name="timing" value="<?php echo $contactDetails['timing']; ?>">
                    </div>
                    <div class="input-box">
                      <span class="details">Address:</span>
                      <input type="text" id="address" name="address" value="<?php echo $contactDetails['address']; ?>">
                    </div>

                </div>
                <div class="button">
                        <input type="submit" value="Update">
                </div>
            </form>
        </div>
    </main>
    <!-- Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../../js/animate.js"></script>
</body>
</html>
