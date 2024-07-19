<?php
session_start();
require_once('../config/dbConnection.php');

// Fetch today's appointments
$todaysAppointmentsResult = $conn->query("SELECT * FROM appointments WHERE date = CURDATE()");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="flex flex-col">
  <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
      <div class="overflow-hidden">
        <table class="min-w-full text-left text-sm font-light text-surface dark:text-white">
          <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
            <tr>
              <th scope="col" class="px-6 py-4">#</th>
              <th scope="col" class="px-6 py-4">Name</th>
              <th scope="col" class="px-6 py-4">Email</th>
              <th scope="col" class="px-6 py-4">Service</th>
              <th scope="col" class="px-6 py-4">Date</th>
              <th scope="col" class="px-6 py-4">Time</th>
              <th scope="col" class="px-6 py-4">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($todaysAppointmentsResult->num_rows > 0): ?>
              <?php
              $index = 1;
              while ($row = $todaysAppointmentsResult->fetch_assoc()) {
                  echo "<tr class='border-b border-neutral-200 dark:border-white/10'>";
                  echo "<td class='whitespace-nowrap px-6 py-4 font-medium'>" . $index++ . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['name']) . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['email']) . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['service']) . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['date']) . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['time']) . "</td>";
                  echo "<td class='whitespace-nowrap px-6 py-4'>" . htmlspecialchars($row['status']) . "</td>";
                  echo "</tr>";
              }
              ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="px-6 py-4 text-center">No appointments found for today</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
