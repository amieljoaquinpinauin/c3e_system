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

    // Pagination parameters
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 8;
    $offset = ($currentPage - 1) * $limit;

    // Show leave requests based on the user's position
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$nameFilter = isset($_GET['name']) ? $_GET['name'] : ''; // Updated to name filter

$sql = "SELECT * FROM file_leave WHERE ";

// Apply user-specific filter for HR
if (strcasecmp($userPosition, 'HR') === 0) {
    $sql .= "(position = ? OR position = 'HR')";
} else {
    $sql .= "position = ?";
}

// Apply status filter if not 'all'
if ($statusFilter !== 'all') {
    if ($statusFilter === 'rejected') {
        $sql .= " AND status = 'rejected'";
    } else {
        $sql .= " AND status = ?";
    }
}

// Apply name filter if not empty
if (!empty($nameFilter)) {
    // Using LIKE with wildcard % to search for the name anywhere in the field
    $sql .= " AND name LIKE ?";
    $nameFilter = '%' . $nameFilter . '%'; // Add wildcards to the search term
}

// Add ORDER BY clause to sort the results in descending order based on the 'date' column
$sql .= " ORDER BY date DESC";

// Continue with the rest of your code...



    // Count total leave requests without applying limit and offset
    $countQuery = $sql; // Copy the SQL query for counting
    $countQuery = str_replace('SELECT *', 'SELECT COUNT(*) AS total', $countQuery); // Modify to count rows
    $stmtCount = $conn->prepare($countQuery);
    // Bind parameters based on user position, status filter, and name filter
if ($userPosition !== 'Super Admin') {
    if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
        if (!empty($nameFilter)) {
            $stmtCount->bind_param("sss", $userPosition, $statusFilter, $nameFilter);
        } else {
            $stmtCount->bind_param("ss", $userPosition, $statusFilter);
        }
    } else {
        if (!empty($nameFilter)) {
            $stmtCount->bind_param("ss", $userPosition, $nameFilter);
        } else {
            $stmtCount->bind_param("s", $userPosition);
        }
    }
} else {
    if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
        if (!empty($nameFilter)) {
            $stmtCount->bind_param("ss", $statusFilter, $nameFilter);
        } else {
            $stmtCount->bind_param("s", $statusFilter);
        }
    }
}

    $stmtCount->execute();
    $countResult = $stmtCount->get_result();
    $countRow = $countResult->fetch_assoc();
    $totalLeaveRequests = $countRow['total'];
    $stmtCount->close();

    // Add pagination
    $sql .= " LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters based on user position, status filter, and reason filter
    if ($userPosition !== 'Super Admin') {
        if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
            if (!empty($reasonFilter)) {
                $stmt->bind_param("ssii", $userPosition, $statusFilter, $reasonFilter, $limit, $offset);
            } else {
                $stmt->bind_param("ssii", $userPosition, $statusFilter, $limit, $offset);
            }
        } else {
            if (!empty($reasonFilter)) {
                $stmt->bind_param("siii", $userPosition, $reasonFilter, $limit, $offset);
            } else {
                $stmt->bind_param("sii", $userPosition, $limit, $offset);
            }
        }
    } else {
        if ($statusFilter !== 'all' && $statusFilter !== 'rejected') {
            if (!empty($reasonFilter)) {
                $stmt->bind_param("sii", $statusFilter, $reasonFilter, $limit, $offset);
            } else {
                $stmt->bind_param("sii", $statusFilter, $limit, $offset);
            }
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    <link rel="stylesheet" href="design_web.css">
    <title>Leave List</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">
    <!-- Bootstrap viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body>
<?php include 'navbar.php'; ?>

<div class="container" id="leaveListContainer">
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

    <!-- Name filter
<form action="leave_list.php" method="get" class="mb-3">
    <label for="nameFilter">Search by Name:</label>
    <input type="text" id="nameFilter" name="name" value="<?php echo ltrim(rtrim($nameFilter, '%'), '%'); ?>" placeholder="Enter name">
    <button type="submit" class="btn btn-primary">Search</button>
</form> -->


    <!-- Display leave requests based on the user's position and status filter -->
    <?php if (!empty($leaveRequests)) : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <!-- <th>Position</th> Added this column -->
                    <?php if (strcasecmp($userPosition, 'HR') === 0 || strcasecmp($userPosition, 'Super Admin') === 0) : ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($leaveRequests as $request) : ?>
    <tr>
        <td><?php echo strlen($request['name']) > 18 ? substr($request['name'], 0, 18) . '...' : $request['name']; ?></td>
        <td><?php echo $request['date']; ?></td>
        <td><?php echo $request['type']; ?></td>
        <td><?php echo $request['duration_from']; ?></td>
        <td><?php echo $request['duration_to']; ?></td>
        <td><?php echo strlen($request['reason']) > 18 ? substr($request['reason'], 0, 18) . '...' : $request['reason']; ?></td>
        <?php
            // Apply inline style based on status
            $statusStyle = '';
            $borderRadius = ''; // Initialize border radius as empty
            $padding = ''; // Initialize padding as empty
            $textColor = ''; // Initialize text color as empty
            switch(strtolower($request['status'])) {
                case 'pending':
                    $statusStyle = 'background-color: #fff0d4;';
                    $borderRadius = 'border-radius: 10px;'; // Apply border radius
                    $padding = 'padding: 10px;'; // Apply padding
                    $textColor = 'color: #a97c25;'; // Apply text color
                    break;
                case 'rejected':
                    $statusStyle = 'background-color: #ffe2e2;';
                    $borderRadius = 'border-radius: 10px;'; // Apply border radius
                    $padding = 'padding: 10px;'; // Apply padding
                    $textColor = 'color: #db1a1a;'; // Apply text color
                    break;
                case 'approved':
                    $statusStyle = 'background-color: #d0ffdb;';
                    $borderRadius = 'border-radius: 10px;'; // Apply border radius
                    $padding = 'padding: 10px;'; // Apply padding
                    $textColor = 'color: #18b025;'; // Apply text color
                    break;
                default:
                    $statusStyle = ''; // No style for other cases
                    // No need to apply border radius, padding, or text color for other cases
            }
            // Combine status style, border radius, padding, and text color
            $combinedStyle = $statusStyle . $borderRadius . $padding . $textColor;
        ?>
        <td><span style="<?php echo $combinedStyle; ?>"><b><?php echo $request['status']; ?></b></span></td>

        <!-- Display buttons based on status -->
        <?php if (strcasecmp($userPosition, 'HR') === 0 || strcasecmp($userPosition, 'Super Admin') === 0) : ?>
            <td>
                <?php if (strcasecmp($request['status'], 'approved') === 0) : ?>
                    <!-- Disable the check button if the status is approved -->
                    <button id ="btnleave" class="btn btn-success" onclick="approveLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')" disabled>
                        <i class="bi bi-check"></i>
                    </button>
                    <!-- Enable the reject button if the status is approved -->
                    <button id ="btnleave" class="btn btn-danger" onclick="rejectLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')">
                        <i class="bi bi-x"></i>
                    </button>
                <?php elseif (strcasecmp($request['status'], 'rejected') === 0) : ?>
                    <!-- Enable the check button if the status is rejected -->
                    <button id ="btnleave" class="btn btn-success" onclick="approveLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')">
                        <i class="bi bi-check"></i>
                    </button>
                    <!-- Disable the reject button if the status is rejected -->
                    <button id ="btnleave" class="btn btn-danger" onclick="rejectLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')" disabled>
                        <i class="bi bi-x"></i>
                    </button>
                <?php else : ?>
                    <!-- Enable both buttons if the status is pending -->
                    <button id ="btnleave" class="btn btn-success" onclick="approveLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')">
                        <i class="bi bi-check"></i>
                    </button>
                    <button id ="btnleave" class="btn btn-danger" onclick="rejectLeave(<?php echo $request['id']; ?>, '<?php echo $userPosition; ?>')">
                        <i class="bi bi-x"></i>
                    </button>
                <?php endif; ?>

                <!-- Modify the "View" button to trigger the modal -->
                <button class="btn btn-primary btnViewLeave" onclick="viewLeaveDetails('<?php echo htmlentities($request['name']); ?>', '<?php echo htmlentities($request['date']); ?>', '<?php echo htmlentities($request['type']); ?>', '<?php echo htmlentities($request['duration_from']); ?>', '<?php echo htmlentities($request['duration_to']); ?>', '<?php echo htmlentities($request['reason']); ?>', '<?php echo htmlentities($request['status']); ?>')">
                    <i class="bi bi-eye bi-5xs"></i>
                </button>

            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>


            </tbody>
        </table>
    <?php else : ?>
        <p>No leave requests found for <?php echo $userPosition; ?>.</p>
    <?php endif; ?>

<!-- Add this modal markup at the end of your HTML body -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLeaveModalLabel">Leave Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="viewName"></span></p>
                <p><strong>Date:</strong> <span id="viewDate"></span></p>
                <p><strong>Type:</strong> <span id="viewType"></span></p>
                <p><strong>Duration From:</strong> <span id="viewDurationFrom"></span></p>
                <p><strong>Duration To:</strong> <span id="viewDurationTo"></span></p>
                <p><strong>Reason:</strong> <span id="viewReason"></span></p>
                <p><strong>Status:</strong> <span id="viewStatus"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="pagination">
    <?php
    // Calculate total pages
    $totalPages = ceil($totalLeaveRequests / $limit);

    // Display pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        // Check if the current page matches $i
        $currentPageStyle = ($i == $currentPage) ? 'background-color: #0000FF; color: #ffffff;' : '';
        $hoverAttributes = ($i != $currentPage) ? 'onmouseover="this.style.backgroundColor=\'#0000FF\'; this.style.color=\'#ffffff\'" onmouseout="this.style.backgroundColor=\'#ffffff\'; this.style.color=\'#0000FF\'"' : '';

        echo "<a href='leave_list.php?page=$i&name=" . urlencode($nameFilter) . "' style='text-decoration: none;'><div style='display: inline-block; margin-right: 5px; padding: 5px; border: 2px solid #ccc; $currentPageStyle' $hoverAttributes>$i</div></a>";
    }

    // Display next button if not on the last page
    if ($currentPage < $totalPages) {
        echo "<a href='leave_list.php?page=" . ($currentPage + 1) . "'></a>";
    }
    ?>
</div>









<script>
// Add class name btnViewLeave to the button and change the onclick function accordingly
function viewLeaveDetails(name, date, type, durationFrom, durationTo, reason, status) {
    console.log("View button clicked!");
    console.log("Name:", name);
    console.log("Date:", date);
    console.log("Type:", type);
    console.log("Duration From:", durationFrom);
    console.log("Duration To:", durationTo);
    console.log("Reason:", reason);
    console.log("Status:", status);

    document.getElementById('viewName').innerText = name;
    document.getElementById('viewDate').innerText = date;
    document.getElementById('viewType').innerText = type;
    document.getElementById('viewDurationFrom').innerText = durationFrom;
    document.getElementById('viewDurationTo').innerText = durationTo;
    document.getElementById('viewReason').innerText = reason;
    document.getElementById('viewStatus').innerText = status;

    $('#viewLeaveModal').modal('show');
}



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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</body>
</html>

