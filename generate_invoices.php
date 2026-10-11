<?php
include 'db_connection.php';

// Start session and get the logged-in daycare_id
$daycare_id = $_SESSION['user_id']; // Assuming 'user_id' stores daycare_id
$message = '';

// Handle form submission to generate invoices
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_month = $_POST['month']; // Format: 2025-04
   $fee_per_day = '100'; // setting default per day fee
    // Fetch all children
    $children_sql = "SELECT * FROM daycare_children";
    $children_result = mysqli_query($conn, $children_sql);

    while ($child = mysqli_fetch_assoc($children_result)) {
        $child_id = $child['id'];
       

        // Count present days (count of checked_in records)
        $present_sql = "SELECT COUNT(*) AS present_days FROM attendance 
            WHERE daycare_id = '$daycare_id' 
            AND child_id = '$child_id'
            AND DATE_FORMAT(checked_in, '%Y-%m') = '$selected_month'";
        
        $present_result = mysqli_query($conn, $present_sql);
        $present_row = mysqli_fetch_assoc($present_result);
        $present_days = $present_row['present_days'];

        // Calculate total working days in selected month
        $year = substr($selected_month, 0, 4);
        $month = substr($selected_month, 5, 2);
        $total_days_in_month = date('t', strtotime("$year-$month-01"));

        // Calculate absent days (optional)
        $absent_days = $total_days_in_month - $present_days;

        // Calculate total amount
        $total_amount = $present_days * $fee_per_day;
       $status='Pending';
        // Insert into invoices
        $insert_sql = "INSERT INTO invoices 
            (daycare_id, child_id, month_year, present_days, absent_days, fee_per_day, total_amount,status) 
            VALUES 
            ('$daycare_id', '$child_id', '$selected_month', '$present_days', '$absent_days', '$fee_per_day', '$total_amount','$status')";

        mysqli_query($conn, $insert_sql);
    }

    $message = "Monthly invoices generated successfully!";
}

// Fetch invoices
$invoices_sql = "SELECT invoices.*, daycare_children.name FROM invoices 
    JOIN daycare_children ON invoices.child_id = daycare_children.id 
    WHERE invoices.daycare_id = '$daycare_id'
    ORDER BY generated_at DESC";

$invoices_result = mysqli_query($conn, $invoices_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Invoices</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="main-content">
<br>
<br>
    <h2>Generate Monthly Invoices</h2>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="month">Select Month</label>
            <input type="month" id="month" name="month" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Invoices</button>
    </form>

    <h3 class="mt-5">Generated Invoices</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Child Name</th>
                <th>Month</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Fee/Day</th>
                <th>Total Amount</th>
                <th>Generated At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($invoices_result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($invoices_result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['month_year']); ?></td>
                        <td><?php echo htmlspecialchars($row['present_days']); ?></td>
                        <td><?php echo htmlspecialchars($row['absent_days']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['fee_per_day'], 2)); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['total_amount'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($row['generated_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center">No invoices generated yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
