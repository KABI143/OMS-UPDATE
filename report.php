<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hydraulic_oil_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$from_date = '';
$to_date = '';
$report_data = null;

// Process form data and generate report
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_report'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $query = "SELECT date, oil_name, machine_name, quantity_used, used_by, used_reason 
              FROM oil_usage 
              WHERE date BETWEEN '$from_date' AND '$to_date'";

    $report_data = $conn->query($query);

    if (!$report_data) {
        die("Error executing query: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oil Usage Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="header-content">
        <img class="logo-left" src="assets/taro.png" alt="Left Logo">
        <h1>HYDRAULIC OIL MANAGEMENT</h1>
        <img class="logo-right" src="assets/tex.png" alt="Right Logo">
    </div>
</header>

<div class="main-content">
    <h2>Oil Usage Report</h2>
    <p>Report from <strong><?= htmlspecialchars($from_date) ?></strong> to <strong><?= htmlspecialchars($to_date) ?></strong></p>

    <?php if ($report_data && $report_data->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Oil Name</th>
                    <th>Machine Name</th>
                    <th>Liters Used</th>
                    <th>Used By</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $report_data->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['oil_name']) ?></td>
                        <td><?= htmlspecialchars($row['machine_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity_used']) ?></td>
                        <td><?= htmlspecialchars($row['used_by']) ?></td>
                        <td><?= htmlspecialchars($row['used_reason']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No data found for the selected date range.</p>
    <?php endif; ?>

    <a href="oil_usage_report.php" class="btn-back">Back to Report Generation</a>
</div>

<style>
/* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 80px;
}

/* Header styles */
header {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 10;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 20px;
}

header h1 {
    font-size: 24px;
    color: #0072ff;
}

/* Main content */
.main-content {
    max-width: 1200px;
    width: 90%;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

.main-content h2 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #0072ff;
}

.main-content p {
    font-size: 18px;
    margin-bottom: 20px;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

table th {
    background-color: #0072ff;
    color: white;
}

table td {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #eaf0f7;
}

/* Button styles */
.btn-back {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    font-size: 18px;
    color: #fff;
    background-color: #0072ff;
    text-decoration: none;
    border-radius: 5px;
}

.btn-back:hover {
    background-color: #005bb5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-content h2 {
        font-size: 24px;
    }

    table th, table td {
        font-size: 14px;
        padding: 8px;
    }

    .btn-back {
        font-size: 16px;
        padding: 8px 16px;
    }
}

.logo-left, .logo-right {
    width: 60px;
    height: auto;
}
</style>
</body>
</html>

<?php
$conn->close();
?>
