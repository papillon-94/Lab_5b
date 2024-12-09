<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "Lab_5b";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start session

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = trim($_POST['matric']);
    $password = trim($_POST['password']);

    // Prepare SQL query to fetch the user by matric
    $sql = "SELECT password FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the entered password against the hashed password in the database
            if (password_verify($password, $row['password'])) {
                $_SESSION['matric'] = $matric;
                header("Location: display_user.php");
                exit;
            } else {
                $message = "<p style='color: red; text-align: center;'>Invalid username or password.</p>";
            }
        } else {
            $message = "<p style='color: red; text-align: center;'>Invalid username or password.</p>";
        }

        $stmt->close();
    } else {
        $message = "<p style='color: red; text-align: center;'>Error preparing the SQL statement.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            background: #2575fc;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #6a11cb;
        }

        a {
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #6a11cb;
        }

        .message {
            margin-bottom: 15px;
        }

        .register-link {
            color: white;
            margin-top: 10px;
            display: block;
            text-align: center;
            font-size: 14px;
        }

        .register-link a {
            color: red; /* Change the link color to red */
            text-decoration: none; /* Optional: Remove underline */
        }

        .register-link a:hover {
            text-decoration: underline; /* Optional: Add underline on hover */
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h2>Login</h2>
        <label for="matric">Matric:</label>
        <input type="text" name="matric" id="matric" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" name="submit" value="Login">

        <!-- Display the message inside the form -->
        <?php echo $message; ?>

        <!-- Registration link -->
        <p class="register-link">
            Don't have an account? <a href="register.php">Register here</a>.
        </p>
    </form>
</body>

</html>
