<?php
include 'db_connection.php'; // Connection to your MySQL DB
$user_name = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'parent'; 

// Insert user into the users table
$sql = "INSERT INTO users (name, address, phone, email, password, role) VALUES ('$name', '$address', '$phone', '$email', '$password', '$role')";
if (mysqli_query($conn, $sql)) {
$user_name = $name;
$message = "User profile for $user_name created successfully!";
} else {
$message = "Error: " . $sql . "<br>" . mysqli_error($conn);
}
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>


<link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Sign Up in DayCare Center</h1>
<div class="container">


<!-- Display success or error message -->
<?php if (!empty($message)) : ?>
<div class="alert alert-success">
<?php echo htmlspecialchars($message); ?>
</div>
<?php endif; ?>

<form method="POST" action="signup.php">
<div class="form-group">
<label for="name">Name</label>
<input type="text" class="form-control" id="name" name="name" required>
</div>
<div class="form-group">
<label for="address">Address</label>
<textarea class="form-control" id="address" name="address" required>
    
</textarea>
</div>
<div class="form-group">
<label for="phone">Phone Number</label>
<input type="number" class="form-control" id="phone" name="phone" required>
</div>
<div class="form-group">
<label for="email">Email Address</label>
<input type="email" class="form-control" id="email" name="email" required>
</div>
<div class="form-group">
<label for="password">Password</label>
<input type="password" class="form-control" id="password" name="password" required>
</div>

<button type="submit" class="btn btn-primary">Sign Up</button>
</form>

<p>Already have an account? <a href="index.php">Sign In</a></p>
</div>
</body>
</html>
