<?php
session_start(); // Start the session
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['requestId']) && isset($_GET['nextPosition']) && isset($_GET['status'])) {
    $requestId = $_GET['requestId'];
    $nextPosition = $_GET['nextPosition'];
    $status = $_GET['status'];

    // Get the current position and status of the request
    $sqlGetCurrentPosition = "SELECT position, status FROM file_leave WHERE id = ?";
    $stmtGetCurrentPosition = $conn->prepare($sqlGetCurrentPosition);
    $stmtGetCurrentPosition->bind_param("i", $requestId);
    $stmtGetCurrentPosition->execute();
    $stmtGetCurrentPosition->bind_result($currentPosition, $currentStatus);
    $stmtGetCurrentPosition->fetch();
    $stmtGetCurrentPosition->close();

    // Debugging statements
    echo "Current Position: $currentPosition, Next Position: $nextPosition, Status: $status";

    // Validate the workflow transition based on the current and next positions
    $validTransition = true;

    if ($currentPosition === 'HR' && $nextPosition === 'Super Admin' && $status === 'pending') {
        // Allow HR to Super Admin only if the status is 'pending'
        $validTransition = true;
    } elseif ($currentPosition === 'Super Admin' && ($nextPosition === 'approved' || $nextPosition === 'rejected') && $status === 'pending') {
        // Allow Super Admin to change status to 'approved' or 'rejected'
        $validTransition = true;
    }

    // Check if the user is lower than Super Admin and set status to 'pending'
    if ($validTransition && $currentPosition !== 'Super Admin') {
        $status = 'pending';
    }

    // Rest of the code remains the same

    if ($validTransition) {
        // Update the position to the next stage and change status accordingly
        $sqlUpdate = "UPDATE file_leave SET position = ?, status = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssi", $nextPosition, $status, $requestId);

        if ($stmtUpdate->execute()) {
            echo "Workflow: Move to $nextPosition stage and status set to '$status'.";
        } else {
            echo "Error updating position or status: " . $stmtUpdate->error;
        }

        $stmtUpdate->close();
    } else {
        echo "Invalid workflow transition. Current Position: $currentPosition, Next Position: $nextPosition";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
