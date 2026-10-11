<?php
include 'db_connection.php'; // Connection to your MySQL DB
$user_name = '';
$message = '';

// Insert New User
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Either 'carer' or 'manager'

    // Insert into users table
    $sql = "INSERT INTO users (name, address, phone, email, password, role) VALUES ('$name', '$address', '$phone', '$email', '$password', '$role')";
    if (mysqli_query($conn, $sql)) {
        $user_name = $name;
        $message = "User profile for $user_name created successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM users WHERE id = '$delete_id'";
    if (mysqli_query($conn, $sql_delete)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch all users
$sql_fetch = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>

<!-- Bootstrap CSS Link -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
</head>
<body>

   <?php include 'sidebar.php'; ?>
    <div class="main-content">
    <br><br><br>
    <center><h4>Add Up in DayCare Center</h4></center>

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-success mt-3">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Form Start -->
    <form method="POST" action="manage_account.php" class="mt-4">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="role">Register as</label>
            <select class="form-control" id="role" name="role">
                <option value="carer">Carer</option>
                <option value="manager">Manager</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
    <!-- Form End -->

    <!-- Users Table -->
    <h4 class="mt-5">Registered Users</h4>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td>
                            <a href="signup.php?delete_id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



</body>
</html>

<?php
// Close DB connection
mysqli_close($conn);
?>
