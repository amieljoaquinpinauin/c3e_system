<?php
// Include your db_connection.php file
require_once('db_connection.php');

// Fetch the last name and first name from the POST request
$lastName = $_POST['lastName'];
$firstName = $_POST['firstName'];

$response = array();

// Prepare and execute the SQL query
$sql = "SELECT * FROM employee WHERE last_name = ? AND first_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $lastName, $firstName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Employee found, fetch data
    $row = $result->fetch_assoc();
    
    // Prepare response data
    $response['success'] = true;
    $response['fullName'] = $row['last_name'] . ", " . $row['first_name'];
    $response['employeeId'] = $row['id']; // Assuming id is the primary key of the employee table
} else {
    // Employee not found
    $response['success'] = false;
    $response['message'] = "Employee not found.";
}

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close statement
$stmt->close();
?>
