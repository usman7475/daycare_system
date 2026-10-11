<?php
include 'db_connection.php';


// Step 1: Get logged-in daycare_id from session
$daycare_id = $_SESSION['user_id'];
$message = '';

// Handle payment form submission
if (isset($_POST['pay_now']) && isset($_POST['invoice_id'])) {
    $invoice_id = intval($_POST['invoice_id']);
    
    // Update invoice status to "Paid"
    $update_sql = "UPDATE invoices SET status = 'Paid' WHERE id = '$invoice_id' ";
    if (mysqli_query($conn, $update_sql)) {
        $message = "Payment successful. Invoice marked as Paid.";
    } else {
        $message = "Error updating payment status.";
    }
}

// Step 2: Fetch all child IDs and names belonging to this daycare
$child_ids = [];
$child_names = [];

$sql_children = "SELECT id, name FROM daycare_children WHERE daycare_id = '$daycare_id'";
$result_children = mysqli_query($conn, $sql_children);

if (mysqli_num_rows($result_children) > 0) {
    while ($child = mysqli_fetch_assoc($result_children)) {
        $child_ids[] = $child['id']; 
        $child_names[$child['id']] = $child['name'];
    }
}

// Step 3: Fetch invoices
$invoices = [];
if (!empty($child_ids)) {
    $child_ids_string = implode(',', $child_ids);
    $sql_invoices = "SELECT * FROM invoices WHERE child_id IN ($child_ids_string) ORDER BY generated_at DESC";
    $result_invoices = mysqli_query($conn, $sql_invoices);

    if (mysqli_num_rows($result_invoices) > 0) {
        while ($invoice = mysqli_fetch_assoc($result_invoices)) {
            $invoices[] = $invoice;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap Modal JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h4 class="mb-4">Generated Invoices</h4>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Invoice ID</th>
                <th>Child Name</th>
                <th>Month-Year</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Fee Per Day</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Generated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($invoices)) : ?>
                <?php foreach ($invoices as $invoice) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['id']); ?></td>
                        <td><?php echo htmlspecialchars($child_names[$invoice['child_id']] ?? 'Unknown Child'); ?></td>
                        <td><?php echo htmlspecialchars($invoice['month_year']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['present_days']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['absent_days']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['fee_per_day']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['status'] ?? 'Pending'); ?></td>
                        <td><?php echo htmlspecialchars($invoice['generated_at']); ?></td>
                        <td>
                            <?php if (($invoice['status'] ?? 'Pending') != 'Paid') : ?>
                                <button class="btn btn-primary btn-sm pay-now-btn" data-toggle="modal" data-target="#paymentModal" data-invoice-id="<?php echo $invoice['id']; ?>">
                                    Pay Now
                                </button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm" disabled>Paid</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10" class="text-center">No invoices found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pay Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="invoice_id" id="invoice_id">
                <div class="form-group">
                    <label>Credit/Debit Card Number</label>
                    <input type="number" class="form-control"  required>
                </div>
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" class="form-control" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label>CVV Code</label>
                    <input type="password" class="form-control"  required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="pay_now" class="btn btn-primary">Pay Now</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
    $(document).ready(function(){
        $('.pay-now-btn').click(function(){
            var invoiceId = $(this).data('invoice-id');
            $('#invoice_id').val(invoiceId);
        });
    });
</script>

</body>
</html>
