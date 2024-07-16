<?php
    //logout logic
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])){
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to a different page after logout
        header("Location: ../../index.php");
        exit();
    }
?>