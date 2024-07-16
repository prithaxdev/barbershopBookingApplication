<?php
// Start Session
session_start();
// Include database connection
require_once('./config/dbConnection.php');
// CHeck if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // GEt username and password from POST data
    $username = $_POST['username'];
    $password = $_POST['password'];
    // EScape special characters to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    // SQL query to select user from database
    $sql = "SELECT * FROM tblbarber WHERE UserName='$username' AND Password='$password'";
    // EXecute query
    $result = $conn->query($sql);
    // IF one row is returned, user is authenticated
    if ($result->num_rows == 1) {
        // Set username in session
        $_SESSION['username'] = $username;

        // Alert the success message
        echo "<script>alert('Sign In Successfully.');</script>";

        // Redirect to dashboard
        echo "<script>window.location.href = './dashboard/dashboard.php';</script>";
        exit();
    } else {
       // Set error message for incorrect credentials
       $error_message = "Invalid username or password!";
       // Alert the error message
       echo "<script>alert('$error_message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook || Sign In</title>
    <!-- Style -->
    <link rel="stylesheet" href="./src/css/logIn.css">
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
</head>
<body>
    <div class="cursor"></div>
    <div class="form">
        <form id="signin-form" class="form__content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validateForm()">
            <h2>Log In</h2>
            <div class="form__box">
                <input 
                class="form__input"
                placeholder="Enter Username"
                type="text" id="username" name="username">
                <label
                class="form__label"
                for="username">Username</label>
                <div class="form__shadow"></div>
            </div>
            <div class="form__box">
                <input 
                class="form__input"
                type="password" id="password" name="password" placeholder="Enter Password">
                <label
                class="form__label" for="password">Password</label>
                <div class="form__shadow"></div>
            </div>
            <div class="form__button">
                <input
                class="form__submit" 
                type="submit" value="Log In">
            </div>
            <div class="back_home">
                <a href="../index.php">Back to Home</a>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./src/js/login.js"></script>
    <script src="../js/animate.js"></script>
    <script>
    const validateForm = () => {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if (username.trim() === "" || password.trim() === "") {
            alert("Username and password are required.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>

</body>
</html>
