<?php
include 'db_connection.php';


// Step 1: Get logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id
$message = '';

// Step 2: Fetch all child IDs belonging to this daycare
$child_ids = [];
$sql_children = "SELECT id, name FROM daycare_children WHERE daycare_id = '$daycare_id'";
$result_children = mysqli_query($conn, $sql_children);

$child_names = [];
if (mysqli_num_rows($result_children) > 0) {
    while ($child = mysqli_fetch_assoc($result_children)) {
        $child_ids[] = $child['id']; // Collect IDs
        $child_names[$child['id']] = $child['name']; // Map ID to Name
    }
}

// Step 3: If child IDs found, fetch attendance records
$attendance_records = [];
if (!empty($child_ids)) {
    $child_ids_string = implode(',', $child_ids); // e.g., 1,2,3
    $sql_attendance = "SELECT * FROM attendance WHERE child_id IN ($child_ids_string) ORDER BY checked_in DESC";
    $result_attendance = mysqli_query($conn, $sql_attendance);

    if (mysqli_num_rows($result_attendance) > 0) {
        while ($attendance = mysqli_fetch_assoc($result_attendance)) {
            $attendance_records[] = $attendance;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content container mt-4">
    <h4>Attendance Records</h4>

   <?php echo $daycare_id;  ?>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Child's Name</th>
                <th>Checked In</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($attendance_records)) : ?>
                <?php foreach ($attendance_records as $record) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                        <td><?php echo htmlspecialchars($child_names[$record['child_id']] ?? 'Unknown Child'); ?></td>
                        <td><?php echo htmlspecialchars($record['checked_in']); ?></td>
                    </tr>
                <?php endforeach; ?>
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
