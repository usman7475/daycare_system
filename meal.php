<?php
include 'db_connection.php';

// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id


// Fetch all meal records for display
$sql_meals = "SELECT * FROM meals_schedule ORDER BY meal_date ASC";
$result_meals = mysqli_query($conn, $sql_meals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meals Schedule</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'navbar.php'; ?>
<div class="main-content container mt-4">

    <h4>Daily/Weekly Meals Schedule</h4>


    <!-- Display the meals table -->
    <h4 class="mt-5">Meals Schedule</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Meal Time</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_meals) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result_meals)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['meal_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['meal_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['meal_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No meal schedules found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
