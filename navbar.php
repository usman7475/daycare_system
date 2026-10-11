<?php
if (!isset($_SESSION['user_id'])) {
// Redirect to index page if the user is not logged in
header("Location: index.php");
exit(); // Ensure the script stops executing after the redirect
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Navbar</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar">
<div class="container">
<a href="#" class="navbar-brand">Daycare System</a>
<button class="navbar-toggler" onclick="toggleNavbar()">
&#9776; <!-- Hamburger icon -->
</button>
<div class="navbar-menu">
<ul>

<li><a href="parent_profile.php"> Profile Section</a></li>

<li><a href="add_child.php">Child Section</a></li>

<li><a href="child_attendence.php">Child Attendence</a></li>

<li><a href="meal.php">Meal Schedule</a></li>
<li><a href="child_activity.php">Child Activity</a></li>
<li><a href="child_payment.php">Fee</a></li>


<li><a href="child_notifications.php">Notification</a></li>
<li><a href="parent_chat.php">Chat</a></li>

<li><a href="logout.php">Logout</a></li>

</ul>
</div>
</div>
</nav>

<script>
function toggleNavbar() {
const menu = document.querySelector('.navbar-menu');
menu.classList.toggle('active');
}
</script>
</body>
</html>
