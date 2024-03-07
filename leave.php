<?php
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $date = $_POST["date"];
    $leaveType = $_POST["leaveType"];
    $durationFrom = $_POST["durationFrom"];
    $durationTo = $_POST["durationTo"];
    $reason = $_POST["reason"];

    // Insert data into file_leave table
    $sql = "INSERT INTO file_leave (name, date, type, duration_from, duration_to, reason) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $date, $leaveType, $durationFrom, $durationTo, $reason);

    if ($stmt->execute()) {
        echo "Leave request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>File for Leave</title>
    <meta charset="utf-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="c3e_style.css">
    <!-- Bootstrap viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>

<div class="container" id = "FOL_container">
<h2 class="mb-4"><b>FILE FOR LEAVE</b></h2>

    <form method="POST" action="leave.php">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
            </div>
            <div class="form-group col-md-6">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
        </div>
        <div class="form-group">
            <label for="leaveType">Type of Leave:</label>
            <select class="form-control" id="leaveType" name="leaveType">
                <option value="sick">Sick Leave</option>
                <option value="vacation">Vacation Leave</option>
                <option value="personal">Personal Leave</option>
                <option value="maternity">Maternity Leave</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="durationFrom">Duration From:</label>
                <input type="date" class="form-control" id="durationFrom" name="durationFrom">
            </div>
            <div class="form-group col-md-6">
                <label for="durationTo">To:</label>
                <input type="date" class="form-control" id="durationTo" name="durationTo">
            </div>
        </div>
        <div class="form-group">
            <label for="reason">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" rows="9" placeholder="Enter reason"></textarea>
        </div>
        <div class="d-flex justify-content-between">
			<a href="#" onclick="history.back();" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Submit</button>
        </div>
    </form>
</div>

<!-- Bootstrap Icons (Bi) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">

</body>
</html>
