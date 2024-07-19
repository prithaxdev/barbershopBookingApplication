<?php
// Include PHPMailer Library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Error Reporting and Display
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Autoload PHPMailer and other dependencies
require './vendor/autoload.php';
// Include logout file and database connection
require_once('../dashboard/logout.php');
require_once("../config/dbConnection.php");

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cancel'])) {
        // Handle appointment cancellation
        $appointment_id = $_POST['appointment_id'];
        $sql = "UPDATE appointments SET status = 'Cancelled' WHERE id = $appointment_id";
        if ($conn->query($sql) === TRUE) {
            // Fetch user email and other details
            $fetchDetailsQuery = "SELECT email, service, date, time FROM appointments WHERE id = $appointment_id";
            $result = $conn->query($fetchDetailsQuery);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_email = $row['email'];
                $service = $row['service'];
                $date = $row['date'];
                $time = $row['time'];
                // Send cancellation email
                $email_body = "Your appointment for $service on $date at $time has been cancelled.";
                sendEmail($user_email, "Appointment Cancelled", $email_body);
            }
            $message = "Appointment cancelled successfully!";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = './dashboard.php';</script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['accept'])) {
        // Handle appointment acceptance
        $appointment_id = $_POST['appointment_id'];
        $sql = "UPDATE appointments SET status = 'Accepted' WHERE id = $appointment_id";
        if ($conn->query($sql) === TRUE) {
            // Fetch user email and other details
            $fetchDetailsQuery = "SELECT email, service, date, time FROM appointments WHERE id = $appointment_id";
            $result = $conn->query($fetchDetailsQuery);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_email = $row['email'];
                $service = $row['service'];
                $date = $row['date'];
                $time = $row['time'];
                // Send acceptance email
                $email_body = "Your appointment for $service on $date at $time has been accepted.";
                sendEmail($user_email, "Appointment Accepted", $email_body);
            }
            $message = "Appointment accepted successfully!";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = './dashboard.php';</script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body){
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->Port = 587; // Your SMTP port
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'iCoder4693@gmail.com'; // SMTP username
        $mail->Password = 'phyazhqtedckuylk'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->setFrom('iCoder4693@gmail.com', 'iCoder'); // Set From address
        $mail->addAddress($to); // Add recipient
        $mail->Subject = $subject; // Set email subject
        $mail->Body = $body; // Set email body

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Appointments</title>
   
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Font Awesomem CDN Link -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />

     <!-- Favicon Icon -->
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-1.png" sizes="32x32"> -->
    <link rel="icon" type="image/x-icon" href="../favicon/favicon-2.png" sizes="32x32">
    <!-- <link rel="icon" type="image/x-icon" href="./favicons/favicon-3.png"> -->
    
     <!-- Style -->
     <link rel="stylesheet" href="../src/css/Style.css">
     <link rel="stylesheet" href="../src/css/appointment.css">

     <style>
        .accept {
            background-color: blue;
            color: white;
        }


        .cancel {
            background-color: red;
            color: white;
        }
        .cancel:hover{
          background-color: crimson;
        }
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
        <li class="active">
          <a href="#">
            <!-- Link to Appointment page -->
            <i class="fa-solid fa-calendar-days"></i>
            <span>Appointment</span>
          </a>
        </li>
        <li>
          <a href="./contact-us.php">
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
            <h2 class="cursor-scale small">Appointment</h2>
          </div>
          <div class="user--info">
            <!-- User info content -->
          </div>
        </div>
      <!-- Main content for Appointment page -->
      <div class="appointment-container">
      <div class="appointment-list">
        <h3>Appointment List</h3>
        <table>
          <tr>
            <th data-label="Name">Name</th>
            <th data-label="Email">Email</th>
            <th data-label="Service">Service</th>
            <th data-label="Data">Date</th>
            <th data-label="Time">Time</th>
            <th data-label="Status">Status</th>
            <th data-label="Action">Action</th>
          </tr>
          <?php
            // Fetch appointments from the database
            $sql = "SELECT * FROM appointments ORDER BY id DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['service'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    if ($row['status'] == 'Pending') {
                        echo "<form method='post'  onsubmit='return confirmAction(event)'>";
                        echo "<input type='hidden' name='appointment_id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' name='accept' class='accept' >Accept</button>";
                        echo "<button type='submit' name='cancel' class='cancel' >Cancel</button>";
                        echo "</form>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No appointments found</td></tr>";
            }
          ?>
        </table>
      </div>
      </div>
    </div>
    <!-- Script -->
    <!-- Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../../js/animate.js"></script>
    <script>
      // JavaScript to handle action confirmation
      function confirmAction(event) {
          const form = event.target;
          const action = form.querySelector('button[type="submit"][name="accept"]') && form.querySelector('button[type="submit"][name="accept"]').clicked ? 'accept' : 'cancel';
          const confirmationMessage = action === 'accept' ? 'Are you sure you want to accept this appointment?' : 'Are you sure you want to cancel this appointment?';
          return confirm(confirmationMessage);
      }

      // Add click event listeners to mark which button was clicked
      document.querySelectorAll('button[name="accept"], button[name="cancel"]').forEach(button => {
          button.addEventListener('click', function() {
              this.clicked = true;
          });
      });

    </script>
</body>
</html>