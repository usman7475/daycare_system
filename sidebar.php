<?php
include 'db_connection.php';
if (!isset($_SESSION['user_id'])) {
    // Redirect to index page if the user is not logged in
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DayCare</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
           
            left: 0;
            background-color: #343a40;
            transition: all 0.3s ease;
            z-index: 999;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
          padding: 55px 30px 30px 30px;
            transition: all 0.3s ease;
        }

        /* Media Queries for Mobile View */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                padding-top: 60px; /* Ensure content is below the navbar */
            }
        }

        /* Header Padding for the Main Content */
        .main-content h2 {
            margin-top: 20px;
        }
        .space {
          font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar with Toggle Button -->
<nav class="navbar navbar-dark bg-dark">
    <button class="navbar-toggler d-block d-md-none" type="button" id="sidebarToggle">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand ml-4 space" href="#">Daycare Dashboard</a>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebarMenu">
    <br><br><br>
    <a href="daycare_dashboard.php">Daycare Dashboard</a>
     <a href="attendance_report.php">Attendance Report</a>
  
   
     <a href="activity_report.php">Activity Report</a>
     <a href="meals_schedule.php">Meal Schedule</a>
  <a href="daycare_child.php">Child Section</a>
    <a href="generate_invoices.php">Generate Invoice</a>
    
    
      <a href="manage_account.php">Manage Account</a>
    <a href="daycare_chat.php">Chat</a>
    <a href="notifications.php">Notification</a>
    <a href="logout.php">Logout</a>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        // Toggle sidebar visibility on button click
        $('#sidebarToggle').on('click', function(){
            $('#sidebarMenu').toggleClass('show');
        });
    });
</script>

</body>
</html>
