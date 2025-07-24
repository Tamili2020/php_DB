<!DOCTYPE html>
<html>
<head>
    <title>Employee Data from Azure SQL</title>
    <style>
        body { font-family: Arial; background-color: #f2f9ff; padding: 30px; text-align: center; }
        table { border-collapse: collapse; width: 80%; margin: auto; background: white; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #0078D4; color: white; }
        h1 { color: #0078D4; }
    </style>
</head>
<body>
    <h1>Azure SQL Employee Data</h1>
    <?php
    // Azure SQL connection using sqlsrv
    $serverName = "tcp:mydemovm.database.windows.net,1433";
    $connectionOptions = array(
        "Database" => "mydemodb",
        "Uid" => "azureadmin",
        "PWD" => "Welcome@123456",  // No {} here
        "Encrypt" => 1,
        "TrustServerCertificate" => 0,
        "LoginTimeout" => 30
    );

    // Connect
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        die("<p style='color:red;'>❌ Connection failed: " . print_r(sqlsrv_errors(), true) . "</p>");
    }

    // Query data
    $sql = "SELECT EmployeeID, FirstName, LastName, Department FROM dbo.Employees"; // Use dbo. schema
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die("<p style='color:red;'>❌ Query failed: " . print_r(sqlsrv_errors(), true) . "</p>");
    }

    // Display table
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
    echo "</table>";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>
</body>
</html>
