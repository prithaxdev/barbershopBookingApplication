<?php
session_start();
include_once("../config/dbConnection.php");

// Fetch services from the database
$selectQuery = "SELECT * FROM services";
$result = $conn->query($selectQuery);

// Display alert messages if session variables are set
if (isset($_SESSION['success'])) {
    echo "<script>alert('" . $_SESSION['success'] . "')</script>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "')</script>";
    unset($_SESSION['error']);
}

// Update service if form is submitted
if (isset($_POST['update'])) {
    $serviceId = $_POST['service_id'];
    $serviceName = $_POST['service-name'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];

    // Update service details
    $updateQuery = "UPDATE services SET service_name=?, description=?, cost=? WHERE service_id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdi", $serviceName, $description, $cost, $serviceId);

    if ($stmt->execute()) {
        // Check if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_temp = $_FILES['image']['tmp_name'];
            $image_folder = '../src/uploaded_img/' . $image_name;

            // Move uploaded image to destination folder
            if (move_uploaded_file($image_temp, $image_folder)) {
                // Update image in the database
                $updateImageQuery = "UPDATE services SET image=? WHERE service_id=?";
                $stmt_img = $conn->prepare($updateImageQuery);
                $stmt_img->bind_param("si", $image_name, $serviceId);
                if ($stmt_img->execute()) {
                    $_SESSION['success'] = "Service and image updated successfully.";
                } else {
                    $_SESSION['error'] = "Error updating image: " . $conn->error;
                }
                $stmt_img->close();
            } else {
                $_SESSION['error'] = "Error moving uploaded file. Debug info: " . $_FILES['image']['error'];
            }
        } else {
            $_SESSION['success'] = "Service updated successfully.";
        }
    } else {
        $_SESSION['error'] = "Error updating service: " . $conn->error;
    }

    // Redirect back to manage_services.php
    header("Location: ./manage_services.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Manage Services</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="../favicon/favicon-2.png" sizes="32x32">

    <!-- Style -->
    <link rel="stylesheet" href="../src/css/manage_services.css">
    <link rel="stylesheet" href="../src/css/PopUp.css">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="back">
       <a href="./services.php"><i class='bx bx-left-arrow-circle'></i></a>
    </div>
    <div class="container"> 
        <div class="manage_services">
            <h1 class="title">Manage Services</h1>
            <table class="display_table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../src/uploaded_img/<?= $row['image'] ?>?<?= time() ?>" alt="Service Image" height="100"></td>
                                <td><?= $row['service_name'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $row['cost'] ?></td>
                                <!-- <td>
                                    <a class="btn edit-btn" data-service-id="<?= $row['service_id'] ?>"><i class='bx bxs-edit'></i>Edit</a>
                                    <a href="./delete_service.php?id=<?= $row['service_id'] ?>" class="btn"><i class='bx bx-trash'></i>Delete</a>
                                </td> -->
                                <td>
                                    <a class="btn edit-btn" data-service-id="<?= $row['service_id'] ?>"><i class='bx bxs-edit'></i>Edit</a>
                                    <a href="#" class="btn delete-btn" data-delete-url="./delete_service.php?id=<?= $row['service_id'] ?>"><i class='bx bx-trash'></i>Delete</a>
                                </td>
                            </tr>
                            <!-- Popup box for updating service -->
                            <div id="popup-box-<?= $row['service_id'] ?>" class="popup">
                                <div class="popup_content">
                                    <span class="close-btn">&times;</span>
                                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
                                        <input type="hidden" name="service_id" value="<?= $row['service_id'] ?>">
                                        <div class="title">
                                            <h1>UPDATE SERVICE</h1>
                                        </div>
                                        <div class="input-control">
                                            <label for="service-name">Service Name:</label>
                                            <input type="text" name="service-name" id="service-name" value="<?= $row['service_name'] ?>">
                                        </div>
                                        <div class="input-control">
                                            <label for="description">Description:</label>
                                            <textarea name="description" id="description"><?= $row['description'] ?></textarea>
                                        </div>
                                        <div class="input-control">
                                            <label for="cost">Cost:</label>
                                            <input type="text" name="cost" id="cost" value="<?= $row['cost'] ?>">
                                        </div>
                                        <div class="input-control">
                                            <label for="image">Update Image:</label>
                                            <input type="file" accept="image/png, image/jpeg, image/jpg" name="image" id="image">
                                        </div>
                                        <button style="padding:10px; font-weight:bold; margin-top:20px;" type="submit" name="update">UPDATE SERVICE</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No services found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script -->
    <script>
        // JavaScript to handle showing/hiding popup boxes
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const serviceId = this.getAttribute('data-service-id');
                const popup = document.getElementById('popup-box-' + serviceId);
                popup.style.display = 'block';
            });
        });

        document.querySelectorAll('.close-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.popup').style.display = 'none';
            });
        });

        // JavaScript to handle delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default action
                const deleteUrl = this.getAttribute('data-delete-url');
                if (confirm("Are you sure you want to delete this service?")) {
                    window.location.href = deleteUrl; // Redirect to the delete URL if confirmed
                }
            });
        });

        // JavaScript to handle update confirmation
        function confirmUpdate() {
            return confirm("Are you sure you want to update this service?");
        }
    </script>
</body>
</html>
