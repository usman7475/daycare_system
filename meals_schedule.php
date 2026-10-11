<?php
include 'db_connection.php';

// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id
$message = '';

// Handle form submission for adding meal schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_date = $_POST['meal_date'];
    $meal_time = $_POST['meal_time'];
    $meal_description = $_POST['meal_description'];

    // Insert meal record
    $sql = "INSERT INTO meals_schedule (daycare_id, meal_date, meal_time, meal_description) 
            VALUES ('$daycare_id', '$meal_date', '$meal_time', '$meal_description')";
    if (mysqli_query($conn, $sql)) {
        $message = "Meal schedule recorded successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all meal records for display
$sql_meals = "SELECT * FROM meals_schedule WHERE daycare_id = '$daycare_id' ORDER BY meal_date ASC";
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

<?php include 'sidebar.php'; ?>
<div class="main-content">
<br>
<br>
    <h2>Create Daily/Weekly Meals Schedule</h2>

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Form to submit meal schedule -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="meal_date">Meal Date</label>
            <input type="date" class="form-control" id="meal_date" name="meal_date" required>
        </div>
        <div class="form-group">
            <label for="meal_time">Meal Time</label>
            <select class="form-control" id="meal_time" name="meal_time" required>
                <option value="">--Select Meal Time--</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Snack">Snack</option>
                <option value="Dinner">Dinner</option>
            </select>
        </div>
        <div class="form-group">
            <label for="meal_description">Meal Description</label>
            <input type="text" class="form-control" id="meal_description" name="meal_description" placeholder="Enter Meal Details" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Meal</button>
    </form>

    <!-- Display the meals table -->
    <h3 class="mt-5">Meals Schedule</h3>
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
