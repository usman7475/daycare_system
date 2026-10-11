<?php
include 'db_connection.php';

// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id
$message = '';

// Handle form submission for sending notification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $notification_message = mysqli_real_escape_string($conn, $_POST['message']);
    $notification_type = $_POST['notification_type'];

    // Insert notification record
    $sql = "INSERT INTO notifications (daycare_id, title, message, notification_type) 
            VALUES ('$daycare_id', '$title', '$notification_message', '$notification_type')";
    if (mysqli_query($conn, $sql)) {
        $message = "Notification sent successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all notifications for display
$sql_notifications = "SELECT * FROM notifications WHERE daycare_id = '$daycare_id' ORDER BY created_at DESC";
$result_notifications = mysqli_query($conn, $sql_notifications);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications & Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="main-content">
<br>
<br>
    <h2>Send Out Message / Notification</h2>

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Form to send a new notification -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Notification Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Notification Title" required>
        </div>
        <div class="form-group">
            <label for="notification_type">Notification Type</label>
            <select class="form-control" id="notification_type" name="notification_type" required>
                <option value="">--Select Type--</option>
                <option value="Event">Event</option>
                <option value="Holiday">Holiday</option>
                <option value="Emergency">Emergency</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Notification Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Notification</button>
    </form>

    <!-- Display the notifications table -->
    <h3 class="mt-5">Sent Notifications</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_notifications) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result_notifications)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['notification_type']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No notifications sent yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
