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

// Fetch user details for the form
$matric = $_GET['matric'] ?? '';

if ($matric) {
    $sql = "SELECT matric, name, role AS accessLevel FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: display_user.php");
    exit;
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedMatric = $_POST['matric'];
    $updatedName = $_POST['name'];
    $updatedAccessLevel = $_POST['accessLevel'];

    $updateSql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sss", $updatedName, $updatedAccessLevel, $updatedMatric);
    $stmt->execute();
    $stmt->close();

    header("Location: display_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
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
            background-color: #f5f5f5;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button, a {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            background-color: #6a11cb;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover, a:hover {
            background-color: #2575fc;
        }

        a {
            background-color: #ccc;
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Update User</h2>
    <form action="update_user.php?matric=<?= htmlspecialchars($matric) ?>" method="post">
        <label for="matric">Matric</label>
        <input type="text" id="matric" name="matric" value="<?= htmlspecialchars($user['matric']) ?>" readonly>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="accessLevel">Access Level</label>
        <input type="text" id="accessLevel" name="accessLevel" value="<?= htmlspecialchars($user['accessLevel']) ?>" required>

        <button type="submit">Update</button>
        <a href="display_user.php">Cancel</a>
    </form>
</body>
</html>
