<?php
session_start();
include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'daycare', 'parent', 'manager', 'carer'

    // Sanitize inputs to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare SQL query (NO status check now)
    $query = "SELECT id, email,name, password, role FROM users WHERE email = ? AND role = ?";

    // Prepare and execute statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // If the password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // Store actual role from DB

            // Redirect based on the role
            if ($user['role'] === 'parent') {
                header("Location: parent_profile.php");
            } elseif ($user['role'] === 'daycare' || $user['role'] === 'manager' || $user['role'] === 'carer') {
                header("Location: daycare_dashboard.php");
            } else {
                // Unknown role fallback
                $error_message = "Unknown role.";
            }
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        // Either the email or role is incorrect
        $error_message = "Invalid email, password, or role selection.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<br>
<h1>Sign In DayCare Center</h1>
<div class="container">

<?php if (isset($error_message)): ?>
<div class="error"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label for="role">I am a:</label>
    <select name="role" required>
        <option value="parent">Parent</option>
        <option value="daycare">Admin</option>
        <option value="carer">Carer</option>
        <option value="manager">Manager</option>
    </select>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Sign In</button>
</form>

<p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>
</body>
</html>
