<?php
include 'db_connection.php';


// Initialize variables
$daycare_id = $_SESSION['user_id'];
$message = '';
$child_name = '';
$age = '';
$edit_id = '';

// Add OR Update child record if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    
    // Check if we are editing
    if (!empty($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $sql_update = "UPDATE daycare_children SET name='$name', age='$age' WHERE id='$edit_id' AND daycare_id='$daycare_id'";
        if (mysqli_query($conn, $sql_update)) {
            $message = "Child profile updated successfully!";
        } else {
            $message = "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Inserting new record
        $created_at = date('Y-m-d H:i:s');
        $sql_insert = "INSERT INTO daycare_children (daycare_id, name, age, created_at) VALUES ('$daycare_id', '$name', '$age', '$created_at')";
        if (mysqli_query($conn, $sql_insert)) {
            $message = "Child profile for $name created successfully!";
        } else {
            $message = "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
        }
    }
}

// Fetch record for edit
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql_edit = "SELECT * FROM daycare_children WHERE id='$edit_id' AND daycare_id='$daycare_id'";
    $edit_result = mysqli_query($conn, $sql_edit);
    if (mysqli_num_rows($edit_result) > 0) {
        $row = mysqli_fetch_assoc($edit_result);
        $child_name = $row['name'];
        $age = $row['age'];
    }
}

// Delete child record if delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM daycare_children WHERE id='$delete_id' AND daycare_id='$daycare_id'";
    if (mysqli_query($conn, $sql_delete)) {
        $message = "Child record deleted successfully!";
    } else {
        $message = "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch all child records 
$sql = "SELECT * FROM daycare_children ";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add/Edit Child</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content p-4">
    <br>
    <br>
    <h2><?php echo isset($edit_id) && !empty($edit_id) ? 'Edit Child Profile' : 'Add Child Profile'; ?></h2>

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($edit_id); ?>">
        <div class="form-group">
            <label for="name">Child's Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($child_name); ?>">
        </div>
        <div class="form-group">
            <label for="age">Child's Age</label>
            <input type="number" class="form-control" id="age" name="age" required value="<?php echo htmlspecialchars($age); ?>">
        </div>
        <button type="submit" class="btn btn-success"><?php echo isset($edit_id) && !empty($edit_id) ? 'Update Child' : 'Add Child'; ?></button>
        <?php if (!empty($edit_id)): ?>
            <a href="daycare_child.php" class="btn btn-secondary">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <!-- Searchable Table -->
    <h3 class="mt-5">Children List</h3>
    <table id="childrenTable" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Child's Name</th>
                <th>Age</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <!-- Edit button -->
                            <a href="?edit_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <!-- Delete button -->
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No children added yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#childrenTable').DataTable({
            "paging": true,
            "searching": true
        });
    });
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
