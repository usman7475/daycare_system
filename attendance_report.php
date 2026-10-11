<?php
include 'db_connection.php';

// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id
$message = '';

// Fetch child names from daycare_children table 
$sql_children = "SELECT * FROM daycare_children";
$result_children = mysqli_query($conn, $sql_children);

// Handle form submission for adding attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $child_id = $_POST['child_id'];
    $checked_in = $_POST['checked_in'];

    // Insert attendance record
    $sql = "INSERT INTO attendance (daycare_id, child_id, checked_in) VALUES ('$daycare_id', '$child_id', '$checked_in')";
    if (mysqli_query($conn, $sql)) {
        $message = "Attendance for child ID $child_id recorded successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all attendance records for display
$sql_attendance = "SELECT a.*, c.name AS child_name FROM attendance a 
                   JOIN daycare_children c ON a.child_id = c.id 
                   WHERE a.daycare_id = '$daycare_id'";
$result_attendance = mysqli_query($conn, $sql_attendance);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   
 <?php include 'sidebar.php'; ?>
    <div class="main-content">

        <h2>Record Child Attendance</h2>

        <!-- Display success or error message -->
        <?php if (!empty($message)) : ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form to submit attendance -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="child_id">Select Child</label>
                <select class="form-control" id="child_id" name="child_id" required>
                    <option value="">--Select Child--</option>
                    <?php while ($row = mysqli_fetch_assoc($result_children)) : ?>
          <option value="<?php echo $row['id']; ?>">
            
            <?php echo $row['name']; ?>
                

            </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="checked_in">Checked In Time</label>
                <input type="datetime-local" class="form-control" id="checked_in" name="checked_in" required>
            </div>
            <button type="submit" class="btn btn-primary">Record Attendance</button>
        </form>

        <!-- Display the attendance table -->
        <h3 class="mt-5">Attendance Records</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child's Name</th>
                    <th>Checked In</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_attendance) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result_attendance)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['checked_in']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


