<?php
session_start();
require_once('../config/dbConnection.php');

// Initialize filters and CSV generation
$filter = isset($_POST['filter']) ? $_POST['filter'] : '';
$searchService = isset($_POST['searchService']) ? $_POST['searchService'] : '';
$searchEmail = isset($_POST['searchEmail']) ? $_POST['searchEmail'] : '';

// Apply filter and fetch data
$filterQuery = "WHERE 1=1";
switch ($filter) {
    case 'today':
        $filterQuery .= " AND date = CURDATE()";
        break;
    case 'last_7_days':
        $filterQuery .= " AND date >= CURDATE() - INTERVAL 7 DAY";
        break;
    case 'last_15_days':
        $filterQuery .= " AND date >= CURDATE() - INTERVAL 15 DAY";
        break;
    case 'last_30_days':
        $filterQuery .= " AND date >= CURDATE() - INTERVAL 30 DAY";
        break;
}

if (!empty($searchService)) {
    $filterQuery .= " AND service LIKE '%" . $conn->real_escape_string($searchService) . "%'";
}

if (!empty($searchEmail)) {
    $filterQuery .= " AND email LIKE '%" . $conn->real_escape_string($searchEmail) . "%'";
}

// Fetch filtered data
$dataQuery = "SELECT name, email, service, date, time, status FROM appointments $filterQuery";
$dataResult = $conn->query($dataQuery);

// Handle CSV generation
if (isset($_POST['generateCSV'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=appointments.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Name', 'Email', 'Service', 'Date', 'Time', 'Status'));

    while ($row = $dataResult->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function confirmDownload() {
            return confirm("Are you sure you want to download the CSV file?");
        }
    </script>
</head>
<body>
<div class="p-4">
  <a href="dashboard.php" class="inline-flex items-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back to Dashboard
  </a>
</div>

<div class="p-4">
    <form method="post" id="filterForm" class="mb-4 flex space-x-4">
        <div class="flex-1">
            <label for="filter" class="block text-sm font-medium text-gray-700">Filter by date:</label>
            <select name="filter" id="filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2">
                <option value="" <?php echo ($filter === '') ? 'selected' : ''; ?>>All</option>
                <option value="today" <?php echo ($filter === 'today') ? 'selected' : ''; ?>>Today</option>
                <option value="last_7_days" <?php echo ($filter === 'last_7_days') ? 'selected' : ''; ?>>Last 7 Days</option>
                <option value="last_15_days" <?php echo ($filter === 'last_15_days') ? 'selected' : ''; ?>>Last 15 Days</option>
                <option value="last_30_days" <?php echo ($filter === 'last_30_days') ? 'selected' : ''; ?>>Last 30 Days</option>
            </select>
        </div>
        <div class="flex-1">
            <label for="searchService" class="block text-sm font-medium text-gray-700">Filter by service:</label>
            <input type="text" name="searchService" id="searchService" value="<?php echo htmlspecialchars($searchService); ?>" class="mt-1 block w-full border-solid border-4 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2 outline-none">
        </div>
        <div class="flex-1">
            <label for="searchEmail" class="block text-sm font-medium text-gray-700">Filter by email:</label>
            <input type="text" name="searchEmail" id="searchEmail" value="<?php echo htmlspecialchars($searchEmail); ?>" class="mt-1 block w-full border-solid border-4 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2 outline-none">
        </div>
        <button type="submit" name="applyFilter" class="inline-flex items-center px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
            Apply Filter
        </button>
        <button type="submit" name="generateCSV" onclick="return confirmDownload()" class="inline-flex items-center px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
            Generate CSV
        </button>
    </form>
</div>

<div class="p-4">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light text-gray-900">
                    <thead class="border-b bg-gray-100 font-medium text-gray-900">
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
                        <?php
                        $index = 1;
                        while ($row = $dataResult->fetch_assoc()) {
                            echo "<tr class='border-b border-gray-200'>";
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
