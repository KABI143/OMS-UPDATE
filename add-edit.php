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

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM oil_inventory WHERE id = $id";
    if ($conn->query($delete_query) === TRUE) {
        echo "<script>alert('Oil record deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Handle edit request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_oil'])) {
    $id = intval($_POST['id']);
    $oil_name = $_POST['oil_name'];
    $received_by = $_POST['received_by'];
    $stock_quantity = floatval($_POST['stock_quantity']);

    $update_query = "UPDATE oil_inventory SET oil_name = '$oil_name', stock_quantity = $stock_quantity, received_by = '$received_by' WHERE id = $id";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Oil record updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}

// Fetch all oil records
$fetch_query = "SELECT * FROM oil_inventory";
$result = $conn->query($fetch_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Oil - Hydraulic Oil Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
     <!-- Sidebar -->
     <div class="sidebar">
        <h2><i class=""></i> Oil Management</h2>
        <a href="index.php"><i class="fa-solid fa-gauge"></i>DASHBOARD</a>
        <a href="add_oil.php"><i class="fa-solid fa-plus"></i> Add-Oil</a>
        <a href="add-edit.php"><i class="fa-solid fa-plus"></i>ADD-OIL-MANAGE</a>
        <a href="usage_oil.php"><i class="fa-solid fa-plus"></i> ADD-USEAGE-OIL</a>
        <h3>REPORTS</h3>
        <a href="oil_add_report.php"><i class="fa-solid fa-file-invoice"></i> OIL-ADD</a>
        <a href="oil_usage_report.php"><i class="fa-solid fa-file-invoice"></i> OIL-USEGAE</a>
    </div>

    <!-- Header -->
    <header>
        <img class="logo-left" src="assets/taro.png" alt="Left Logo">
        <h1>HYDRAULIC OIL MANAGEMENT DASHBOARD</h1>
        <img class="logo-right" src="assets/tex.png" alt="Right Logo">
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Oil Name</th>
                <th>Stock Quantity</th>
                <th>Received By</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['oil_name']; ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td><?php echo $row['received_by']; ?></td>
                    <td>
                        <a class="btn-edit" href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a class="btn-delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #333;
        color: #fff;
        padding: 20px;
        overflow-y: auto;
        z-index: 5;
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

    /* Header */
    header {
        position: fixed;
        top: 0;
        left: 250px;
        right: 0;
        background-color: #fff;
        padding: 10px 20px;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header h1 {
        font-size: 24px;
        color: #333;
    }

    .logo-left, .logo-right {
        width: 70px;
        height: auto;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        margin-top: 70px;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        flex-grow: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #333;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f4f4f4;
    }

    tr:hover {
        background-color: #ddd;
    }

    .btn-edit {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-delete {
        background-color: #f44336;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        header {
            left: 0;
            margin-left: 0;
        }

        .main-content {
            margin-left: 0;
            margin-top: 70px;
        }
    }
</style>

</html>

