<?php

    session_start();

    include_once("../config/dbConnection.php");

    // Check if ID is provided and is numeric
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $serviceId = $_GET['id'];

        // Prepare delete query
        $deleteQuery = "DELETE FROM services WHERE service_id = ?";

        // Bind parameters and execute query
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $serviceId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Service deleted successfully.";
        } else {
            $_SESSION['error'] = "Error occurred while deleting service.";
        }
    } else {
        $_SESSION['error'] = "Invalid service ID.";
    }
    // Redirect back to manage services page with alert message
    header("Location: ./manage_services.php");
    exit();
?>
