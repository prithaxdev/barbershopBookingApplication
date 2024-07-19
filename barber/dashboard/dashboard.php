<?php
session_start();
require_once('../config/dbConnection.php');
require_once('../dashboard/logout.php'); // Include the logout file

// Fetch tblbarber from the database
$selectQuery = "SELECT * FROM tblbarber";
$result = $conn->query($selectQuery);

// Initialize alert message
$alertMessage = "";

// Update tblbarber if form is submitted
if (isset($_POST['update'])) {
    $ID = $_POST['ID'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE tblbarber SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $password, $ID);

    if ($stmt->execute() === TRUE) {
        $alertMessage = "Username & Password updated successfully.";
    } else {
        $alertMessage = "Error updating Username & Password!: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Dashboard</title>

    <!-- Style -->
    <link rel="stylesheet" href="../src/css/Style.css">
    <link rel="stylesheet" href="../src/css/profile/popup.css">
    <link rel="stylesheet" href="../src/css/Dashboard.css">
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

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="cursor"></div>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li class="active">
                <a href="#">
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
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Appointment</span>
                </a>
            </li>
            <li>
                <a href="./contact-us.php">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Contact</span>
                </a>
            </li>
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
                <h2 class="cursor-scale small">Dashboard</h2>
            </div>
            <div class="user--info">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <i class="fa-solid fa-user barber-profile cursor-scale small" onclick="showPopup(<?= $row['ID'] ?>)"></i>
                    <div id="popup-box-<?= $row['ID'] ?>" class="popup">
                        <div class="close-btn" onclick="closePopup(<?= $row['ID'] ?>)">&times;</div>
                        <div class="popup_content">
                            <form id="updateForm" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" onsubmit="return confirmUpdate()">
                                <input type="hidden" name="ID" value="<?= $row['ID'] ?>">
                                <div class="input-control">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" value="<?= $row['UserName'] ?>">
                                </div>
                                <div class="input-control">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" value="<?= $row['Password'] ?>">
                                </div>
                                <button type="submit" name="update">UPDATE</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="dashboard-boxes">
            <?php
            // Fetch total appointments
            $totalAppointmentsResult = $conn->query("SELECT COUNT(*) AS total FROM appointments");
            $totalAppointments = $totalAppointmentsResult->fetch_assoc()['total'];

            // Fetch canceled appointments
            $canceledAppointmentsResult = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'Cancelled'");
            $canceledAppointments = $canceledAppointmentsResult->fetch_assoc()['total'];

            // Fetch accepted appointments
            $acceptedAppointmentsResult = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'Accepted'");
            $acceptedAppointments = $acceptedAppointmentsResult->fetch_assoc()['total'];

            // Fetch today's appointments
            $todaysAppointmentsResult = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE date = CURDATE()");
            $todaysAppointments = $todaysAppointmentsResult->fetch_assoc()['total'];

            // Fetch tomorrow's appointments
            $tomorrowsAppointmentsResult = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE date = CURDATE() + INTERVAL 1 DAY");
            $tomorrowsAppointments = $tomorrowsAppointmentsResult->fetch_assoc()['total'];
            ?>
            <div class="dashboard-box cursor-scale" onclick="window.location.href='./totalAppointments.php'">
                <h3>Total Appointments</h3>
                <p><?php echo $totalAppointments; ?></p>
            </div>
            <div class="dashboard-box cursor-scale" onclick="window.location.href='./canceledAppointments.php'">
                <h3>Canceled Appointments</h3>
                <p><?php echo $canceledAppointments; ?></p>
            </div>
            <div class="dashboard-box cursor-scale" onclick="window.location.href='./acceptedAppointments.php'">
                <h3>Accepted Appointments</h3>
                <p><?php echo $acceptedAppointments; ?></p>
            </div>
            <div class="dashboard-box cursor-scale" onclick="window.location.href='./todaysAppointments.php'">
                <h3>Today's Appointments</h3>
                <p><?php echo $todaysAppointments; ?></p>
            </div>
            <div class="dashboard-box cursor-scale" onclick="window.location.href='./tomorrowsAppointments.php'">
                <h3>Tomorrow's Appointments</h3>
                <p><?php echo $tomorrowsAppointments; ?></p>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../../js/animate.js"></script>
    <script>
        function showPopup(id) {
            document.getElementById('popup-box-' + id).style.display = 'block';
        }

        function closePopup(id) {
            document.getElementById('popup-box-' + id).style.display = 'none';
        }

        <?php if ($alertMessage !== ""): ?>
            alert("<?php echo $alertMessage; ?>");
        <?php endif; ?>

        function confirmUpdate() {
            return confirm("Are you sure you want to update the username and password?");
        }
    </script>
</body>
</html>
