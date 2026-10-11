<?php
include 'db_connection.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$current_user_id = $_SESSION['user_id']; // daycare user id (receiver_id)
$user_name=$_SESSION['user_name'];

// Handle sending a new message
if (isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);
      $message_name = $user_name . " : " . $message; 
    $fixed_sender_id = 9; // Always 9 for daycare sending

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $fixed_sender_id, $receiver_id, $message_name);
        $stmt->execute();
    }
}

// Fetch messages
$chat_messages = [];
$selected_parent_id = $current_user_id; // Receiver id is current user

// Check if there are already messages
$msg_query = "SELECT * FROM messages 
              WHERE receiver_id = $current_user_id
              ORDER BY created_at ASC";

$msg_result = mysqli_query($conn, $msg_query);

if ($msg_result === false) {
    die('Error in message query: ' . mysqli_error($conn));
}

$result_get = mysqli_num_rows($msg_result);

if ($result_get > 0) {
    while ($row = mysqli_fetch_assoc($msg_result)) {
        $chat_messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2>Chat with Daycare</h2>
<?php  echo $_SESSION['user_name']; ?>
    <!-- Chat Messages -->
    <?php if (!empty($chat_messages)) : ?>
        <div class="mt-4" style="border: 1px solid #ccc; padding: 15px; height: 400px; overflow-y: scroll;">
            <?php foreach ($chat_messages as $msg) : ?>
                <div class="mb-2">
           

           <?php echo htmlspecialchars($msg['message']); ?>
                    <br>
                    <small><?php echo date('d-m-Y H:i', strtotime($msg['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Send New Message -->
        <form method="POST" action="" class="mt-3">
            <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selected_parent_id); ?>">
            <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                <div class="input-group-append">
                    <button type="submit" name="send_message" class="btn btn-success">Send</button>
                </div>
            </div>
        </form>

    <?php else : ?>
        <!-- New Chat (no previous messages) -->
        <div class="alert alert-info mt-4">No previous chat found. Start a new conversation!</div>
        <form method="POST" action="" class="mt-3">
            <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selected_parent_id); ?>">
            <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Type your first message..." required>
                <div class="input-group-append">
                    <button type="submit" name="send_message" class="btn btn-primary">Start Chat</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
