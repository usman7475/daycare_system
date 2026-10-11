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
        $child_ids[] = $child['id']; // Collect child IDs
        $child_names[$child['id']] = $child['name']; // Map ID to Name
    }
}

// Step 3: If child IDs found, fetch child activities
$child_activities = [];
if (!empty($child_ids)) {
    $child_ids_string = implode(',', $child_ids); // Convert array to comma separated string
    $sql_activities = "SELECT * FROM child_activities WHERE child_id IN ($child_ids_string) ORDER BY activity_date DESC";
    $result_activities = mysqli_query($conn, $sql_activities);

    if (mysqli_num_rows($result_activities) > 0) {
        while ($activity = mysqli_fetch_assoc($result_activities)) {
            $child_activities[] = $activity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Child Activities</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content container mt-4">
    <h4>Child Daily Activities</h4>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered mt-4">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Child's Name</th>
                <th>Activity Date</th>
                <th>Playtime</th>
                <th>Learning Session</th>
                <th>Meal</th>
                <th>Nap</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($child_activities)) : ?>
                <?php foreach ($child_activities as $activity) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($activity['id']); ?></td>
                        <td><?php echo htmlspecialchars($child_names[$activity['child_id']] ?? 'Unknown Child'); ?></td>
                        <td><?php echo htmlspecialchars($activity['activity_date']); ?></td>
                        <td><?php echo htmlspecialchars($activity['playtime']); ?></td>
                        <td><?php echo htmlspecialchars($activity['learning_session']); ?></td>
                        <td><?php echo htmlspecialchars($activity['meal']); ?></td>
                        <td><?php echo htmlspecialchars($activity['nap']); ?></td>
                        <td>
                            <?php if (!empty($activity['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($activity['image']); ?>" width="80" height="80" alt="Activity Image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center">No activity records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
