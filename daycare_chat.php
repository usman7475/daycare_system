<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$current_user_id = $_SESSION['user_id']; // current user id

// Initialize variables
$chat_messages = [];
$selected_parent_id = null;
$parents_result = null;
$no_parents = false;

// Fetch parents
$parents_query = "SELECT id, name FROM users WHERE role = 'parent'";
$parents_result = mysqli_query($conn, $parents_query);

if (!$parents_result || mysqli_num_rows($parents_result) == 0) {
    $no_parents = true;
}

// Handle sending a new message
if (isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $current_user_id, $receiver_id, $message);
        $stmt->execute();
        $stmt->close();
        
        // Refresh messages after sending
        $selected_parent_id = $receiver_id;
    }
}

// Fetch messages if a parent is selected
if (isset($_POST['start_chat']) || isset($_POST['send_message'])) {
    $selected_parent_id = $_POST['receiver_id'];
    
    $msg_query = "SELECT m.*, u.name as sender_name 
                  FROM messages m
                  JOIN users u ON m.sender_id = u.id
                  WHERE (m.sender_id = $current_user_id AND m.receiver_id = $selected_parent_id) 
                  OR (m.sender_id = $selected_parent_id AND m.receiver_id = $current_user_id)
                  ORDER BY m.created_at ASC";
    
    $msg_result = mysqli_query($conn, $msg_query);

    if ($msg_result) {
        while ($row = mysqli_fetch_assoc($msg_result)) {
            $chat_messages[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .chat-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            max-width: 70%;
        }
        .sent {
            background-color: #d4edda;
            margin-left: auto;
        }
        .received {
            background-color: #f8f9fa;
            margin-right: auto;
        }
        .message-info {
            font-size: 0.8em;
            color: #6c757d;
        }
        .no-messages {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        .no-parents {
            padding: 20px;
            background-color: #f8d7da;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Chat with Parents</h2>

    <?php if ($no_parents): ?>
        <div class="no-parents">
            No parents found in the system.
        </div>
    <?php else: ?>
        <!-- Select parent to chat -->
        <form method="POST" action="" class="mb-4">
            <div class="form-group">
                <label>Select Parent</label>
                <select name="receiver_id" class="form-control" required>
                    <option value="">-- Select Parent --</option>
                    <?php 
                    if ($parents_result) {
                        mysqli_data_seek($parents_result, 0); // Reset pointer
                        while ($parent = mysqli_fetch_assoc($parents_result)) : ?>
                            <option value="<?php echo $parent['id']; ?>" <?php echo ($selected_parent_id == $parent['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($parent['name']); ?>
                            </option>
                        <?php endwhile;
                    } ?>
                </select>
            </div>
            <button type="submit" name="start_chat" class="btn btn-primary">Start Chat</button>
        </form>

        <!-- Chat Messages -->
        <?php if ($selected_parent_id): ?>
            <div class="chat-container">
                <?php if (!empty($chat_messages)): ?>
                    <?php foreach ($chat_messages as $msg): ?>
                        <div class="message <?php echo ($msg['sender_id'] == $current_user_id) ? 'sent' : 'received'; ?>">
                            <div class="message-info">
                                <strong><?php echo htmlspecialchars($msg['sender_name']); ?></strong>
                                <span><?php echo date('d-m-Y H:i', strtotime($msg['created_at'])); ?></span>
                            </div>
                            <div class="message-text">
                                <?php echo htmlspecialchars($msg['message']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-messages">
                        No messages yet. Start the conversation!
                    </div>
                <?php endif; ?>
            </div>

            <!-- Send New Message -->
            <form method="POST" action="">
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selected_parent_id); ?>">
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                    <div class="input-group-append">
                        <button type="submit" name="send_message" class="btn btn-success">Send</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>