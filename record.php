<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="c3e_style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Daily Time Record</title>
</head>
<body>

<div class="container" id="record_container">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <h2>Name: <span id="fullName"></span></h2>
            <p>Status: <span id="status">In</span></p>
            <p>Time: <span id="time">12:00 PM</span></p>
            <form method="post" action="record.php" id="searchForm">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" name="lastName" placeholder="Enter last name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" name="firstName" placeholder="Enter first name">
                    </div>
                </div><br>
                <button type="button" class="btn btn-outline-secondary" onclick="handleFormSubmission()">Search</button><br><br>
            </form>
        </div>

        <!-- Right Column -->
        <div class="col-md-6 text-center">
            <p id="currentDate" class="mb-1"></p>
            <p id="currentTime" class="mb-1"></p>
            <img src="path/to/your/image.jpg" alt="User Image" class="img-fluid mt-3"> <br>
        </div>
    </div>

    <!-- Buttons -->
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-start">
            <a href="#" class="btn btn-success" onclick="handleTimeIn()">Time In</a>
                <a href="#" class="btn btn-danger ml-2"><i class="bi bi-arrow-left"></i> Time Out</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-secondary" onclick="redirectToLogin()"> <i class="bi bi-check-circle"></i> Sign In</button>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript for current time and date in Philippines timezone with month, day, year format -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    function redirectToLogin() {
        window.location.href = 'login.html';
    }
</script>

<script>

function handleTimeIn() {
    // Get the employee ID from the data attribute
    var employeeId = document.getElementById("fullName").getAttribute("data-employee-id");

    console.log("Employee ID: " + employeeId);

$.ajax({
    type: 'GET',
    url: 'record.php',
    data: { action: 'timeIn', employeeId: employeeId },
    dataType: 'json',
    success: function (response) {
        alert(response.success);
    },
    error: function (error) {
        console.error(error.responseText);
    }
});
    
}
    
    function handleFormSubmission() {
        // Get the values from the form
        var lastName = document.querySelector('input[name="lastName"]').value;
        var firstName = document.querySelector('input[name="firstName"]').value;

        // Update the full name
        document.getElementById("fullName").innerHTML = lastName + ", " + firstName;
    }

    function updateDateTime() {
        var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
        var currentDate = new Date().toLocaleDateString('en-PH', options);
        var currentTime = new Date().toLocaleTimeString('en-PH');

        document.getElementById("currentDate").innerHTML = "Date: " + currentDate;
        document.getElementById("currentTime").innerHTML = "Time: " + currentTime;

        setTimeout(updateDateTime, 1000); // Update every second
    }

    // Initial call to start updating
    updateDateTime();
</script>

</body>
</html>
