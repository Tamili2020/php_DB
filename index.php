<!DOCTYPE html>
<html>
<head>
    <title>Azure SQL DB Test</title>
</head>
<body style="background-color: #e6f2ff; font-family: Arial, sans-serif;">
    <center>
        <h1>Azure SQL Connection Test</h1>
        <?php
        $serverName = "your-server.database.windows.net";
        $connectionOptions = array(
            "Database" => "your-database",
            "Uid" => "your-username",
            "PWD" => "your-password",
            "Encrypt" => 1,
            "TrustServerCertificate" => 0,
            "LoginTimeout" => 30
        );

        // Establishes the connection
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn) {
            echo "<p style='color: green;'>✅ Connection successful to Azure SQL Database!</p>";
            
            // Optional: run a sample query
            $tsql = "SELECT name FROM sys.databases";
            $stmt = sqlsrv_query($conn, $tsql);

            echo "<h3>Available Databases:</h3><ul>";
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<li>" . $row['name'] . "</li>";
            }
            echo "</ul>";

            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);
        } else {
            echo "<p style='color: red;'>❌ Connection failed.</p>";
            die(print_r(sqlsrv_errors(), true));
        }
        ?>
    </center>
</body>
</html>
