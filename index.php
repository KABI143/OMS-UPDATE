<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hydraulic_oil_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total oil quantity
$total_oil_query = "SELECT total_oil FROM dashboard WHERE id = 1";
$total_oil_result = $conn->query($total_oil_query);
$total_oil = $total_oil_result->fetch_assoc()['total_oil'];

// Fetch individual oil records
$oil_query = "SELECT * FROM oil_inventory";
$oil_result = $conn->query($oil_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hydraulic Oil Management</title>
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
    <header>
        <img class="logo-left" src="assets/taro.png" alt="Left Logo">
        <h1> HYDRAULIC OIL MANAGEMENT-DASBOARD</h1>
        <img class="logo-right" src="assets/tex.png" alt="Right Logo">
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            <!-- Oil Inventory Container -->
            <div class="oil-container">
                <h2>CURRENT-STOCK</h2>
                <div class="oil-list">
                    <?php if ($oil_result->num_rows > 0) : ?>
                        <?php while ($row = $oil_result->fetch_assoc()) : ?>
                            <div class="oil-item-container">
                                <div class="oil-item">
                                    <div class="oil-name"><?php echo $row['oil_name']; ?></div>
                                    <div class="oil-quantity"><?php echo $row['stock_quantity']; ?> L</div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No oil inventory found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<style>
    /* Your existing styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        background-image: linear-gradient(to right, #00c6ff, #0072ff);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

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
        color: #333;
    }

    .logo-left, .logo-right {
        width: 70px;
        height: auto;
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        padding-top: 100px;
        flex-grow: 1;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .content-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
    }

    .oil-container {
        flex: 1;
        min-width: 280px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #eaf0f7;
    }

    .oil-container h2 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #333;
    }

    .oil-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .oil-item-container {
        display: flex;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .oil-item {
        display: flex;
        flex-direction: column;
    }

    .oil-name {
        font-weight: bold;
    }

    .oil-quantity {
        color: #666;
    }

    .oil-list p {
        font-size: 18px;
        color: #ff0000;
    }

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

        .oil-container h2 {
            font-size: 20px;
        }

        .oil-item-container {
            font-size: 16px;
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

        .oil-container h2 {
            font-size: 18px;
        }

        .oil-item-container {
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

        .content-wrapper {
            flex-direction: column;
        }

        header h1 {
            font-size: 22px;
        }

        .oil-container h2 {
            font-size: 16px;
        }

        .oil-item-container {
            font-size: 12px;
        }
    }

    @media (max-width: 360px) {
        .sidebar h2 {
            font-size: 20px;
        }

        .sidebar a {
            font-size: 16px;
        }

        header h1 {
            font-size: 20px;
        }

        .oil-container h2 {
            font-size: 14px;
        }

        .oil-item-container {
            font-size: 12px;
        }
    }
</style>
</html>
