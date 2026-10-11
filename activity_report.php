<?php
include 'db_connection.php';


// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id'];
$message = '';

// Fetch children
$sql_children = "SELECT * FROM daycare_children";
$result_children = mysqli_query($conn, $sql_children);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $child_id = $_POST['child_id'];
    $activity_date = $_POST['activity_date'];
    $playtime = $_POST['playtime'];
    $learning_session = $_POST['learning_session'];
    $meal = $_POST['meal'];
    $nap = $_POST['nap'];

    // Handle Image Upload
    $image_name = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create uploads folder if not exists
        }
        $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Image uploaded successfully
        } else {
            $message = "Image upload failed.";
        }
    }

    // Insert into child_activities table
    $sql = "INSERT INTO child_activities (daycare_id, child_id, activity_date, playtime, learning_session, meal, nap, image) 
            VALUES ('$daycare_id', '$child_id', '$activity_date', '$playtime', '$learning_session', '$meal', '$nap', '$image_name')";

    if (mysqli_query($conn, $sql)) {
        $message = "Activity record for child added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Also delete the image file if exists
    $fetch_img = mysqli_query($conn, "SELECT image FROM child_activities WHERE id = '$delete_id'");
    $img_row = mysqli_fetch_assoc($fetch_img);
    if (!empty($img_row['image']) && file_exists("uploads/" . $img_row['image'])) {
        unlink("uploads/" . $img_row['image']);
    }

    $delete_sql = "DELETE FROM child_activities WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Record deleted successfully!";
    } else {
        $message = "Error deleting record.";
    }
}

// Fetch all child activities
$sql_activities = "SELECT ca.*, dc.name AS child_name 
                   FROM child_activities ca
                   JOIN daycare_children dc ON ca.child_id = dc.id
                   WHERE ca.daycare_id = '$daycare_id'
                   ORDER BY ca.activity_date DESC";
$result_activities = mysqli_query($conn, $sql_activities);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Child Activities</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
<br><br>
<h2>Record Child Daily Activities</h2>

<!-- Success/Error message -->
<?php if (!empty($message)) : ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<!-- Form to submit activities -->
<form method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="child_id">Select Child</label>
        <select class="form-control" id="child_id" name="child_id" required>
            <option value="">--Select Child--</option>
            <?php while ($row = mysqli_fetch_assoc($result_children)) : ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="activity_date">Activity Date</label>
        <input type="date" class="form-control" id="activity_date" name="activity_date" required>
    </div>

    <div class="form-group">
        <label for="playtime">Playtime Details</label>
        <input type="text" class="form-control" id="playtime" name="playtime" placeholder="e.g., Played with blocks, outdoor games">
    </div>

    <div class="form-group">
        <label for="learning_session">Learning Session Details</label>
        <input type="text" class="form-control" id="learning_session" name="learning_session" placeholder="e.g., ABC learning, Story time">
    </div>

    <div class="form-group">
        <label for="meal">Meal Details</label>
        <input type="text" class="form-control" id="meal" name="meal" placeholder="e.g., Ate fruits, milk">
    </div>

    <div class="form-group">
        <label for="nap">Nap Details</label>
        <input type="text" class="form-control" id="nap" name="nap" placeholder="e.g., Slept for 1 hour">
    </div>

    <div class="form-group">
        <label for="image">Upload Activity Image (optional)</label>
        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Add Activity</button>
</form>

<!-- Display the child activities table -->
<h3 class="mt-5">Child Activities Records</h3>

<table class="table table-bordered mt-3">
    <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Child's Name</th>
            <th>Date</th>
            <th>Playtime</th>
            <th>Learning</th>
            <th>Meal</th>
            <th>Nap</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result_activities) > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result_activities)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['activity_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['playtime']); ?></td>
                    <td><?php echo htmlspecialchars($row['learning_session']); ?></td>
                    <td><?php echo htmlspecialchars($row['meal']); ?></td>
                    <td><?php echo htmlspecialchars($row['nap']); ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/<?php echo $row['image']; ?>" width="80" height="80" alt="Activity Image">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete this record?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="9" class="text-center">No activity records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>

</body>
</html>
