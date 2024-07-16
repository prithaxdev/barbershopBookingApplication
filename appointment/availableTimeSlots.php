<?php
// Include database connection
include_once "./barber/config/dbConnection.php";

// Check if the date is provided in the POST request
if(isset($_POST['date'])) {
    // Retrieve selected date from POST data
    $selectedDate = $_POST['date'];
    
    // Query to fetch booked appointments for the selected date
    $sql = "SELECT time FROM appointments WHERE date = '$selectedDate'";
    $result = $conn->query($sql);

    // Array to hold all time slots for the day
    $availableTimes = array();

    // Generate time slots for the entire day
    $startTime = strtotime("06:00");
    $endTime = strtotime("18:00");
    $interval = 30 * 60; // 30 minutes interval

    // Loop through each time slot for the day
    for ($i = $startTime; $i < $endTime; $i += $interval) {
        $timeSlot = date('H:i', $i);
        $availableTimes[] = $timeSlot; // Add time slot to array
    }

    // Check if there are any booked appointments
    if ($result->num_rows > 0) {
        // Fetch booked time slots and remove them from available times
        while ($row = $result->fetch_assoc()) {
            $bookedTime = date('H:i', strtotime($row['time']));
            $key = array_search($bookedTime, $availableTimes); // Find the index of booked time slot
            if ($key !== false) {
                unset($availableTimes[$key]); // Remove booked time slot from available times
            }
        }
    }

    // Send available time slots to the client-side JavaScript as JSON
    echo json_encode(array_values($availableTimes));
}

// Close database connection
$conn->close();
?>
