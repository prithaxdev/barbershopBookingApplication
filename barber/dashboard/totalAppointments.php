<?php
session_start();
require_once('../config/dbConnection.php');
require_once('../dashboard/logout.php');

// Fetch all appointments
$appointmentsResult = $conn->query("SELECT * FROM appointments");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Appointments</title>
    <link rel="stylesheet" href="../src/css/Style.css">
    <link rel="stylesheet" href="../src/css/Dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="./dashboard.php">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Add other menu items as needed -->
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
                <h2>Total Appointments</h2>
            </div>
        </div>
        <div class="appointments-list">
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Customer Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($appointment = $appointmentsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $appointment['id']; ?></td>
                            <td><?php echo $appointment['customer_name']; ?></td>
                            <td><?php echo $appointment['date']; ?></td>
                            <td><?php echo $appointment['time']; ?></td>
                            <td><?php echo $appointment['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
