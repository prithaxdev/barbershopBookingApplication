<?php
session_start();
require_once('../config/dbConnection.php');
require('./fpdf186/fpdf.php'); // Include the FPDF library

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

// Handle PDF generation
if (isset($_POST['generatePDF'])) {
    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Appointments Report', 0, 1, 'C');
            $this->Ln(10);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(10, 10, '#', 1);
            $this->Cell(40, 10, 'Name', 1);
            $this->Cell(50, 10, 'Email', 1);
            $this->Cell(30, 10, 'Service', 1);
            $this->Cell(25, 10, 'Date', 1);
            $this->Cell(20, 10, 'Time', 1);
            $this->Cell(15, 10, 'Status', 1);
            $this->Ln();
        }

        // Page footer
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    $index = 1;
    while ($row = $dataResult->fetch_assoc()) {
        $pdf->Cell(10, 10, $index++, 1);
        $pdf->Cell(40, 10, $row['name'], 1);
        $pdf->Cell(50, 10, $row['email'], 1);
        $pdf->Cell(30, 10, $row['service'], 1);
        $pdf->Cell(25, 10, $row['date'], 1);
        $pdf->Cell(20, 10, $row['time'], 1);
        $pdf->Cell(15, 10, $row['status'], 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'appointments.pdf');
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
        function confirmGeneratePDF() {
            return confirm("Are you sure you want to generate the PDF file?");
        }
    </script>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="../favicon/favicon-2.png" sizes="32x32">
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
        <button type="submit" name="generatePDF" class="inline-flex items-center px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600" onclick="return confirmGeneratePDF();">
            Generate PDF
        </button>
        <button type="submit" name="generateCSV" class="inline-flex items-center px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600" onclick="return confirmDownload();">
            Download CSV
        </button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Name</th>
                <th class="py-2">Email</th>
                <th class="py-2">Service</th>
                <th class="py-2">Date</th>
                <th class="py-2">Time</th>
                <th class="py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $dataResult->fetch_assoc()): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['service']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['date']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['time']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
