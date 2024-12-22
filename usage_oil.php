<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hydraulic_oil_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Oil Usage form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usage_oil'])) {
    $date = $_POST['date'];
    $machine_name = $_POST['machine_name'];
    $oil_name = trim($_POST['oil_name']);  // Trim any extra spaces from the input
    $quantity_used = $_POST['quantity_used'];
    $used_by = $_POST['used_by'];
    $used_reason = $_POST['used_reason'];
    $remarks = $_POST['remarks'];

    // Check if the oil exists in inventory (case-insensitive)
    $checkStockQuery = "SELECT id, stock_quantity, quantity_received FROM oil_inventory WHERE LOWER(TRIM(oil_name)) = LOWER(?)";  // Case-insensitive comparison
    $stmt = $conn->prepare($checkStockQuery);
    if ($stmt === false) {
        // Handle query preparation error
        $alertMessage = "Error preparing query: " . $conn->error;
    } else {
        $stmt->bind_param("s", $oil_name);  // Bind the parameter to prevent SQL injection
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If stock exists, fetch the current stock and oil ID
            $row = $result->fetch_assoc();
            $current_stock = $row['stock_quantity'] + $row['quantity_received'];  // Total available stock
            $oil_id = $row['id'];  // Get the oil_id for insertion into oil_usage table

            // Insert oil usage record into the database
            $query = "INSERT INTO oil_usage (oil_id, oil_name, machine_name, date, quantity_used, used_by, used_reason, remarks) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                // Handle query preparation error
                $alertMessage = "Error preparing query: " . $conn->error;
            } else {
                $stmt->bind_param("isssisss", $oil_id, $oil_name, $machine_name, $date, $quantity_used, $used_by, $used_reason, $remarks);
                
                if ($stmt->execute()) {
                    // Update the stock quantity
                    $new_stock = $current_stock - $quantity_used;
                    $updateStockQuery = "UPDATE oil_inventory SET stock_quantity = ? WHERE id = ?";  // Update using oil_id
                    $stmt = $conn->prepare($updateStockQuery);
                    if ($stmt === false) {
                        // Handle query preparation error
                        $alertMessage = "Error preparing query: " . $conn->error;
                    } else {
                        $stmt->bind_param("ii", $new_stock, $oil_id);

                        if ($stmt->execute()) {
                            $alertMessage = "Oil usage recorded successfully. Current stock: $new_stock liters.";
                        } else {
                            $alertMessage = "Error updating stock: " . $conn->error;
                        }
                    }
                } else {
                    $alertMessage = "Error recording oil usage: " . $conn->error;
                }
            }
        } else {
            // If the oil is not found in inventory
            $alertMessage = "Oil type '$oil_name' not found in inventory. Please check the oil name.";
        }
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usage Oil - Hydraulic Oil Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
     <!-- Sidebar -->
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
        <!-- Header -->
    <header>
        <img class="logo-left" src="assets/taro.png" alt="Left Logo">
        <h1> HYDRAULIC OIL MANAGEMENT-DASBOARD</h1>
        <img class="logo-right" src="assets/tex.png" alt="Right Logo">
    </header>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>ADD-USAGE</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="date">Date</label>
                    <div class="input-wrapper">
                        <input type="date" name="date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="machine_name">MACHINE-NAME</label>
                    <div class="input-wrapper">
                        <input type="text" name="machine_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="oil_name">USED OIL</label>
                    <div class="input-wrapper">
                        <input type="text" name="oil_name" placeholder="USED OIL" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quantity_used">USED OIL LITERS</label>
                    <div class="input-wrapper">
                        <input type="number" name="quantity_used" placeholder="Quantity Used (Liters)" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="used_by">USED-BY</label>
                    <div class="input-wrapper">
                        <input type="text" name="used_by" placeholder="Name of the Person" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="used_reason">REASON</label>
                    <div class="input-wrapper">
                        <input type="text" name="used_reason" placeholder="Reason for usage" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="remarks">REMARKS</label>
                    <div class="input-wrapper">
                        <input type="text" name="remarks" placeholder="Remarks" required>
                    </div>
                </div>
                <button type="submit" name="usage_oil" class="btn-submit">USAGE-OIL</button>
            </form>
        </div>
    </div>

    <?php if (isset($alertMessage)) : ?>
        <script>alert("<?php echo $alertMessage; ?>");</script>
    <?php endif; ?>
</body>
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
        color: #333;
    }

    .logo-left, .logo-right {
        width: 70px;
        height: auto;
    }


/* Main content styles */
.main-content {
    margin-left: 250px;
    padding: 20px;
    padding-top: 80px; /* To prevent content from hiding under header */
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
    width: 100%;
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
    /* Sidebar adjustments */
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

</style>
</html>
