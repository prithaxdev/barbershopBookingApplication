<?php
include_once "../barber/config/dbConnection.php";

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $services = $_POST['services']; // Array of selected services
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Convert the array of services into a comma-separated string
    $serviceList = implode(", ", $services);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO appointments (name, phone, email, service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $serviceList, $date, $time);

    if ($stmt->execute() === TRUE) {
        $message = "Appointment booked successfully!";
        echo "<script type='text/javascript'>alert('$message'); setTimeout(function(){ window.location.href = '../index.php'; }, 1000);</script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Appointment</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="./favicons/favicon-2.png" sizes="32x32">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Style -->
    <link rel="stylesheet" href="../css/Appointment.css">
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="cursor"></div>
    <div class="container">
        <div class="title">
            <span class="cursor-scale small">Appointment</span>
            <span id="close-btn" class="close-btn"><a href="../index.php">&times;</a></span>
        </div>
        <div class="text">
           <marquee behavior="" direction=""> Book your appointment to save salon rush!</marquee>
        </div>
        <form id="appointmentForm" action="" method="POST" onsubmit="return validateForm()">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Name</span>
                    <input type="text" id="name" name="name">
                    <div class="error-message" id="nameError"></div>
                </div>
                <div class="input-box">
                    <span class="details">Phone</span>
                    <input type="text" id="phone" name="phone">
                    <div class="error-message" id="phoneError"></div>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="email" id="email" name="email">
                    <div class="error-message" id="emailError"></div>
                </div>
                <div class="input-box">
                    <span class="details">Services:</span>
                    <select id="services" name="services[]" multiple="multiple" style="width: 100%;">
                        <?php
                            // Query to fetch services from the database
                            $sql = "SELECT service_name FROM services";
                            $result = $conn->query($sql);

                            // If there are results, generate options
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="'. $row['service_name'] .'">'. $row['service_name'] .'</option>';
                                }
                            }
                        ?>
                    </select>
                    <div class="error-message" id="servicesError"></div>
                </div>
                <div class="input-box">
                    <span class="details">Date:</span>
                    <input type="date" name="date" id="date">
                    <div class="error-message" id="dateError"></div>
                </div>
                <div class="input-box">
                    <span class="details">Time:</span>
                    <input type="time" name="time" id="time" class="time-input" min="06:00" max="18:00">
                    <div class="error-message" id="timeError"></div>
                </div>
            </div>
            <div class="button">
                <input type="submit" name="book" value="Book">
            </div>
        </form>
    </div>
    <!-- Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../js/animate.js"></script>

    <script src="../js/appointment.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateInput = document.getElementById("date");
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const todayDate = `${year}-${month}-${day}`;
            
            dateInput.setAttribute("min", todayDate);

            // Initialize Select2 for services dropdown
            $('#services').select2({
                placeholder: "Select Services",
                allowClear: true
            });
        });
    </script>
</body>
</html>
