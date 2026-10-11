
<?php include 'sidebar.php'; ?>
    <div class="main-content">
    <h2>Daycare Dashboard</h2>
    <p>Welcome to the Daycare Management Dashboard. From here, you can manage various aspects of the daycare.</p>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Income Report</h5>
                        <p class="card-text">View detailed income reports for your daycare center.</p>
                        <a href="generate_invoices.php" class="btn btn-primary">Go to Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Report</h5>
                        <p class="card-text">Track children's attendance in the daycare.</p>
                        <a href="attendance_report.php" class="btn btn-primary">Go to Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Activity Report</h5>
                        <p class="card-text">Manage the detail activity of all the childern</p>
                        <a href="activity_report.php" class="btn btn-primary">Go to Activity Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
