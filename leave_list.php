<?php
session_start(); // Start the session
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

// Retrieve the user's position from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT position FROM employee WHERE id = $user_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userPosition = $user['position'];

    // Show leave requests based on the user's position
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

    $sql = "SELECT * FROM file_leave WHERE 1";

    // Apply user-specific filter for HR
    if (strcasecmp($userPosition, 'HR') === 0) {
        $sql .= " AND (position = ? OR position = 'HR')";
    } else {
        $sql .= " AND position = ?";
    }

    // Apply status filter if not 'all'
    if ($statusFilter !== 'all') {
        if ($statusFilter === 'rejected') {
            $sql .= " AND status = 'rejected'";
        } else {
            $sql .= " AND status = ?";
        }
    }

    $stmt = $conn->prepare($sql);

    // Bind parameters based on user position and status filter
    if ($userPosition !== 'Super Admin') {
        if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
            $stmt->bind_param("ss", $userPosition, $statusFilter);
        } else {
            $stmt->bind_param("s", $userPosition);
        }
    } else {
        if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
            $stmt->bind_param("s", $statusFilter);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $leaveRequests = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $leaveRequests[] = $row;
        }
    }

    $stmt->close();
} else {
    // Redirect to the login page if the user's position is not found
    header('Location: login.php');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave List</title>
    <meta charset="utf-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="c3e_style.css">
    <!-- Bootstrap viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>

<div class="container" id="leaveListContainer" style="background-color: white;">
    <h2 class="mb-4"><b>LEAVE REQUEST LIST (<?php echo $userPosition; ?>)</b></h2>

    <!-- Status filter dropdown -->
    <div class="mb-3">
        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter" name="status" onchange="applyStatusFilter(this.value)">
            <option value="all" <?php echo ($statusFilter === 'all') ? 'selected' : ''; ?>>All</option>
            <option value="pending" <?php echo ($statusFilter === 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="approved" <?php echo ($statusFilter === 'approved') ? 'selected' : ''; ?>>Approved</option>
            <option value="rejected" <?php echo ($statusFilter === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
        </select>
    </div>

    <!-- Display leave requests based on the user's position and status filter -->
    <?php if (!empty($leaveRequests)) : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Duration From</th>
                    <th>Duration To</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Position</th> <!-- Added this column -->
                    <?php if (strcasecmp($userPosition, 'HR') === 0 || strcasecmp($userPosition, 'Super Admin') === 0) : ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaveRequests as $request) : ?>
                    <tr>
                        <td><?php echo $request['name']; ?></td>
                        <td><?php echo $request['date']; ?></td>
                        <td><?php echo $request['type']; ?></td>
                        <td><?php echo $request['duration_from']; ?></td>
                        <td><?php echo $request['duration_to']; ?></td>
                        <td><?php echo $request['reason']; ?></td>
                        <td><?php echo $request['status']; ?></td>
                        <td><?php echo $request['position']; ?></td> <!-- Display the "position" of the user -->
                        <?php if (strcasecmp($userPosition, 'HR') === 0 || strcasecmp($userPosition, 'Super Admin') === 0) : ?>
                            <td>
                                <button class="btn btn-success" onclick="approveLeave(<?php echo $request['id']; ?>, 'Super Admin')">Approve</button>
                                <button class="btn btn-danger" onclick="rejectLeave(<?php echo $request['id']; ?>, <?php echo '\'' . $userPosition . '\'';?>)">Reject</button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No leave requests found for <?php echo $userPosition; ?>.</p>
    <?php endif; ?>

    <!-- Back button -->
    <a href="#" onclick="history.back();" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<!-- Bootstrap Icons (Bi) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">

<script>
    function rejectLeave(requestId, nextPosition) {
        // You can use AJAX to send a request to the server to handle the rejection logic
        // For simplicity, you can display an alert for demonstration purposes
        alert("Leave request from: " + requestId + " rejected.");

        // Update the status to 'rejected'
        window.location.href = 'update_position.php?requestId=' + requestId + '&nextPosition=' + nextPosition + '&status=rejected';
    }

    // Existing functions for approval and status filter1
    function approveLeave(requestId, nextPosition) {
        alert("Leave request from: " + requestId + " approved for <?php echo $userPosition; ?>. Moving to " + nextPosition + ".");
        window.location.href = 'update_position.php?requestId=' + requestId + '&nextPosition=' + nextPosition + '&status=approved';
    }

    function applyStatusFilter(status) {
        window.location.href = 'leave_list.php?status=' + status;
    }
</script>

</body>
</html>
