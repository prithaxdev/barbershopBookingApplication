<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log form submission
file_put_contents('debug.log', "Contact form submitted\n", FILE_APPEND);

// Include PHPMailer autoload file
require './vendor/autoload.php';

// Check if form submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $name = htmlentities($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlentities($_POST['message']);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'iCoder4693@gmail.com'; // SMTP username
        $mail->Password = 'phyazhqtedckuylk'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient details
        $mail->setFrom('iCoder4693@gmail.com', 'iCoder'); // Set From address
        $mail->addAddress('iCoder4693@gmail.com', 'iCoder'); // Add recipient
        $mail->addReplyTo($email, $name); // Set Reply-To address

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Contact Form Submission';
        $mail->Body    = "Name: $name <br>Email: $email <br>Message: $message";
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $message";

        // Send email
        $mail->send();
        echo "<script>alert('Message has been sent');</script>"; // Alert on successful send

    } catch (Exception $e) {
        // Log error to debug file
        file_put_contents('debug.log', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n", FILE_APPEND);
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>"; // Alert on send error
    }
} else {
    echo "<script>alert('Invalid request method.');</script>"; // Alert on invalid request method
}

// Redirect back to index.php after processing
echo "<script>window.location='./index.php';</script>";
?>
