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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oil Usage Report</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

 <!-- Sidebar -->
 <div class="sidebar">
        <h2><i class="fa-solid fa-oil-can"></i> Oil Management</h2>
        <a href="index.php"><i class="fa-solid fa-gauge"></i>DASHBOARD</a>
        <a href="add_oil.php"><i class="fa-solid fa-plus"></i> ADD-OIL</a>
        <a href="add-edit.php"><i class="fa-solid fa-plus"></i>ADD-OIL-MANAGE</a>
        <a href="usage_oil.php"><i class="fa-solid fa-plus"></i> ADD-USEAGE-OIL</a>
        <h3>REPORTS</h3>
        <a href="oil_add_report.php"><i class="fa-solid fa-file-invoice"></i> OIL-ADD</a>
        <a href="oil_usage_report.php"><i class="fa-solid fa-file-invoice"></i> OIL-USEGAE</a>
    </div>
<!-- Header -->
     <!-- Header -->
     <header>
        <!-- Header -->
    <header>
        <img class="logo-left" src="assets/taro.png" alt="Left Logo">
        <h1> HYDRAULIC OIL MANAGEMENT-REPORTS</h1>
        <img class="logo-right" src="assets/tex.png" alt="Right Logo">
    </header>
    </header>

<!-- Main Content -->
<div class="main-content">
    <div class="form-container">
        <h2>Generate Oil Usage Report</h2>
        <!-- Date Filter Form -->
        <form method="POST" action="report.php">
            <div class="form-group">
                <label for="from_date">From Date</label>
                <div class="input-wrapper">
                    <input type="date" name="from_date" required>
                </div>
            </div>
            <div class="form-group">
                <label for="to_date">To Date</label>
                <div class="input-wrapper">
                    <input type="date" name="to_date" required>
                </div>
            </div>

            <button type="submit" name="generate_report" class="btn-submit">Generate Report</button>
        </form>
        </div>
        </div>
        
<style>
    /* General reset for padding and margin */
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
        background-image: linear-gradient(to right, #00c6ff, #0072ff);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Sidebar styles */
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        background-color: #333;
        color: #fff;
        padding: 20px;
        overflow-y: auto;
    }

    .sidebar h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .sidebar a {
        display: block;
        color: #fff;
        text-decoration: none;
        padding: 10px;
        margin: 10px 0;
        font-size: 18px;
    }

    .sidebar a:hover {
        background-color: #575757;
    }
    .sidebar .active {
        background-color: #4CAF50;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        padding: 10px 20px;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    header h1 {
        font-size: 24px;
        margin: 0;
        color: #333;
    }

    /* Main content styles */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        padding-top: 80px;
        flex-grow: 1;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Form container */
    .form-container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #eaf0f7;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        font-size: 24px;
        margin-bottom: 15px;
        text-align: center;
    }

    .form-container label {
        display: block;
        margin: 10px 0 5px;
        font-size: 16px;
        color: #34495e;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        background-color: #fff;
        border-radius: 5px;
        padding: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .input-wrapper i {
        margin-right: 10px;
        color: #0072ff;
    }

    .form-group input {
        border: none;
        outline: none;
        padding: 10px;
        width:100%;
        font-size: 16px;
    }

    .btn-submit {
        width: 100%;
        padding: 10px;
        font-size: 18px;
        color: #fff;
        background-color: #0072ff;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 15px;
    }

    .btn-submit:hover {
        background-color: #005bb5;
    }

    /* Report table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #0072ff;
        color: white;
    }

    table td {
        background-color: #f9f9f9;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }

        .main-content {
            margin-left: 220px;
        }

        header h1 {
            font-size: 28px;
        }

        .form-container h2 {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 180px;
        }

        .main-content {
            margin-left: 180px;
        }

        header h1 {
            font-size: 24px;
        }

        .form-container h2 {
            font-size: 18px;
        }

        .form-group input {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
        }

        .main-content {
            margin-left: 0;
        }

        header h1 {
            font-size: 22px;
        }

        .form-container {
            width: 90%;
            margin: 0 auto;
        }

        .form-container h2 {
            font-size: 16px;
        }

        .form-group input {
            font-size: 14px;
        }

        .btn-submit {
            font-size: 16px;
        }
    }

    .logo-left, .logo-right {
        width: 70px;
        height: auto;
    }

</style>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
