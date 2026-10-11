<?php
session_start();
include 'db_connection.php';

$message = "";

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Form submit hone par update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $update_query = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}

// User details fetch karna
$user_query = "SELECT name, email, phone, address FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $row = mysqli_fetch_assoc($user_result);
    $user_name = $row['name'];
    $user_email = $row['email'];
    $user_phone = $row['phone'];
    $user_address = $row['address'];
} else {
    $message = "User not found!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Parent Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    body {
        padding-top: 70px; /* Navbar ki height ke liye space */
    }
    
    /* Fix for navbar appearing behind content */
    .navbar {
        z-index: 1000;
        position: fixed;
        top: 0;
        width: 100%;
    }
    
    /* Ensure content doesn't go under navbar */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: #f8f9fa; /* Match your navbar color */
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Update Parent Profile</h2>


    <?php if (!empty($message)) : ?>
        <div class="alert alert-info mt-3">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-header">
            <h4>Edit Your Details</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_phone); ?>" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user_address); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS CDN (important for toggler to work) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>