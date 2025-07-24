<!DOCTYPE html>
<html>
<head>
    <title>Azure SQL Employee Portal</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f0f8ff;
            padding: 30px;
            text-align: center;
        }
        .btn {
            padding: 12px 25px;
            background-color: #0078D4;
            color: white;
            border: none;
            margin: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-section, .list-section {
            display: none;
            margin-top: 30px;
        }
        input {
            padding: 10px;
            margin: 10px;
            width: 250px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #0078D4;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Azure SQL Employee Portal</h1>
    
    <!-- Buttons -->
    <form method="post">
        <button class="btn" name="action" value="add">Add Employee</button>
        <button class="btn" name="action" value="list">Employee List</button>
    </form>

<?php
// Azure SQL DB connection
$serverName = "tcp:mydemovm.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "mydemodb",
    "Uid" => "azureadmin",
    "PWD" => "Welcome@123456",
    "Encrypt" => true,
    "TrustServerCertificate" => false
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("<p style='color:red;'>❌ Connection failed: " . print_r(sqlsrv_errors(), true) . "</p>");
}

// Show form if 'Add Employee' is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
    echo '
    <div class="form-section">
        <h2>Add New Employee</h2>
        <form method="post">
            <input type="text" name="first_name" placeholder="First Name" required><br>
            <input type="text" name="last_name" placeholder="Last Name" required><br>
            <input type="text" name="department" placeholder="Department" required><br>
            <input class="btn" type="submit" name="submit" value="Save">
        </form>
    </div>
    ';
}

// Insert employee
if (isset($_POST['submit'])) {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $dept = $_POST['department'];

    $insert = "INSERT INTO Employees (FirstName, LastName, Department) VALUES (?, ?, ?)";
    $params = array($first, $last, $dept);
    $stmt = sqlsrv_query($conn, $insert, $params);

    if ($stmt === false) {
        echo "<p style='color:red;'>❌ Insert failed: " . print_r(sqlsrv_errors(), true) . "</p>";
    } else {
        echo "<p style='color:green;'>✅ Employee added successfully!</p>";
    }
}

// Show employee list if clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'list' || isset($_POST['submit'])) {
    $sql = "SELECT EmployeeID, FirstName, LastName, Department FROM Employees";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt !== false) {
        echo '<div class="list-section">';
        echo "<h2>Employee List</h2>";
        echo "<table>";
        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Department</th></tr>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['EmployeeID']}</td>
                    <td>{$row['FirstName']}</td>
                    <td>{$row['LastName']}</td>
                    <td>{$row['Department']}</td>
                  </tr>";
        }
        echo "</table></div>";
        sqlsrv_free_stmt($stmt);
    } else {
        echo "<p style='color:red;'>❌ Query failed: " . print_r(sqlsrv_errors(), true) . "</p>";
    }
}

sqlsrv_close($conn);
?>
</body>
</html>
