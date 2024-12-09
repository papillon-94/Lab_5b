<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
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
            width: 95%;
        }

        input[type="submit"]:hover {
            background: #6a11cb;
        }

        form h2 {
            text-align: center;
        }

        .message {
            text-align: center;
            color: white;
            background: rgba(0, 128, 0, 0.7);
            padding: 10px;
            border-radius: 5px;
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            font-size: 14px;
        }

        .message.error {
            background: rgba(255, 0, 0, 0.7);
        }
    </style>
</head>

<body>
    <?php
    // Database connection details
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'Lab_5b';

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $message = ""; // Initialize message

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $matric = $_POST['matric'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security
        $role = $_POST['role'];

        // SQL query to insert data
        $sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $matric, $name, $password, $role);

        if ($stmt->execute()) {
            $message = "<p class='message'>Registration successful!</p>";
        } else {
            $message = "<p class='message error'>Error: " . $stmt->error . "</p>";
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
    ?>

    <form action="" method="post">
        <h2>Register</h2>
        <label for="matric">Matric:</label>
        <input type="text" name="matric" id="matric" required>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="">Please select</option>
            <option value="lecturer">Lecturer</option>
            <option value="student">Student</option>
        </select>

        <input type="submit" name="submit" value="Register">
        <!-- Display the message inside the form -->
        <?php echo $message; ?>

        <p class="login-link">
            <a href="login.php">Login here</a>.
        </p>
    </form>
</body>

</html>
