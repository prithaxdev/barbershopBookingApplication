<?php
session_start();
include_once('../config/dbConnection.php');
require_once('../dashboard/logout.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are filled
    if (isset($_POST['service-name']) && isset($_POST['description']) && isset($_POST['cost']) && isset($_FILES['image'])) {
        $serviceName = $_POST['service-name'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];
        $image_name = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_folder = '../src/uploaded_img/' . $image_name;

        // Check if service name already exists
        $checkQuery = "SELECT * FROM services WHERE service_name = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $serviceName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Service name already exists.";
        } else {
            // Insert new service into the database
            $insertQuery = "INSERT INTO services (service_name, description, cost, image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssds", $serviceName, $description, $cost, $image_name);
            
            if ($stmt->execute()) {
                if (move_uploaded_file($image_temp, $image_folder)) {
                    $_SESSION['success'] = "Service added successfully. Image uploaded.";
                } else {
                    $_SESSION['error'] = "Error moving uploaded file. Debug info: " . $_FILES['image']['error'];
                }
            } else {
                $_SESSION['error'] = "Error adding service. Please try again.";
            }
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
    }
    // Redirect back to the page
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Display alert messages if session variables are set
if(isset($_SESSION['success'])){
    echo "<script>alert('" . $_SESSION['success'] . "')</script>";
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo "<script>alert('" . $_SESSION['error'] . "')</script>";
    unset($_SESSION['error']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Services</title>

    <!-- Style -->
    <link rel="stylesheet" href="../src/css/Style.css">
    <link rel="stylesheet" href="../src/css/PopUp.css">
    <link rel="stylesheet" href="../src/css/Services.css">
    <style>
        .blur-background {
        filter: blur(5px);
        transition: filter 0.3s ease-in-out;
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="../favicon/favicon-2.png" sizes="32x32">
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
            <li class="active">
                <a href="#">
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
            <li>
                <a href="./contact-us.php">
                    <!-- Link to Contact page -->
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
                <h2 class="cursor-scale small">Services</h2>
            </div>
        </div>
        <main>
            <div class="container">
                <div class="add_box">
                    <div class="content">
                        <i class='bx bx-plus-circle add_icon' id="add_icon"></i>
                        <span class="text">Add Services</span>
                    </div>
                </div>
                <div class="manage_box">
                    <div class="content">
                        <a href="./manage_services.php"><i class='bx bx-right-arrow-circle add_icon' id="add_icon"></i></a>
                        <span class="text">Manage Services</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="popup-box" class="popup">
        <div class="popup_content">
            <span id="close-btn" class="close-btn">&times;</span>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                <div class="title">
                    <h1>Add A NEW SERVICE</h1>
                </div>
                <div class="input-control">
                    <label for="service-name">Service Name:</label>
                    <input type="text" name="service-name" id="service-name">
                </div>
                <div class="input-control">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description"></textarea>
                </div>
                <div class="input-control">
                    <label for="cost">Cost:</label>
                    <input type="text" name="cost" id="cost">
                </div>
                <div class="input-control">
                    <label for="image">Image:</label>
                    <input type="file" accept="image/png, image/jpeg, image/jpg" name="image" id="image">
                </div>
                <button type="submit" name="add">ADD SERVICE</button>
            </form>
        </div>
    </div>
    
    <!-- Script -->
     <!-- Script -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../../js/animate.js"></script>
    <script>
        function validateForm() {
            let serviceName = document.getElementById("service-name").value.trim();
            let description = document.getElementById("description").value.trim();
            let cost = document.getElementById("cost").value.trim();

            // check if any fields is empty
            if (serviceName === "" || description === "" || cost === "") {
                alert("Please fill in all fields.");
                return false;
            }

            // Check if cost is not a number or negative
            if (isNaN(cost)) {
                alert("Please enter a valid number for the cost.");
                return false;
            } else if (parseFloat(cost) < 0) {
                alert("Please enter a non-negative number for the cost.");
                return false;
            }
            return true;
        }

        document.getElementById("add_icon").addEventListener("click", function () {
            document.getElementById("popup-box").style.display = "block";
            document.querySelector(".main--content").classList.add("blur-background");
            // document.querySelector(".sidebar").classList.add("blur-background");
        });

        document.getElementById("close-btn").addEventListener("click", function () {
            document.getElementById("popup-box").style.display = "none";
            document.querySelector(".main--content").classList.remove("blur-background");
            // document.querySelector(".sidebar").classList.remove("blur-background");
        });
    </script>
    <!-- Script -->
    <script src="../src/js/popup.js"></script>
</body>
</html>