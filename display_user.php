<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$username = "root";
$password = "";
$database = "Lab_5b";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete functionality
if (isset($_GET['delete'])) {
    $matricToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", $matricToDelete);
    $stmt->execute();
    $stmt->close();
    header("Location: display_user.php");
    exit;
}

// Fetch users
$sql = "SELECT matric, name, role AS accessLevel FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        .logout-btn {
            margin: 20px;
            text-align: right;
            width: 90%;
            max-width: 800px;
        }

        .logout-btn a {
            text-decoration: none;
            color: #fff;
            background-color: #ff4b5c;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout-btn a:hover {
            background-color: #d43f4a;
        }

        table {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            color: #333;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6a11cb;
            color: #fff;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #2575fc;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
            color: #6a11cb;
        }

        .action-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .no-records {
            text-align: center;
            color: #666;
            padding: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <div class="logout-btn">
        <a href="login.php">Logout</a>
    </div>
    <h2>User List</h2>
    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['matric']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['accessLevel']) . "</td>";
                echo "<td class='action-links'>
                        <a href='update_user.php?matric=" . urlencode($row['matric']) . "'>Update</a>
                        <a href='display_user.php?delete=" . urlencode($row['matric']) . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='no-records'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
