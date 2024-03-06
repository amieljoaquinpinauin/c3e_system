<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Your Page Title</title>
    <style>
        .bone-container {
            background-color: #e3dac9; /* Bone color */
            padding: 20px;
            border-radius: 10px;
        }

        #currentTime {
            font-size: 1.5rem;
            font-weight: bold;
        }

        #currentDate {
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-4 bone-container">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <h2>Name</h2>
            <p>Status: <span id="status">In</span></p>
            <p>Time: <span id="time">12:00 PM</span></p>
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" class="form-control" id="search" placeholder="Type to search">
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6 text-center">
            <p id="currentDate" class="mb-1"></p>
            <p id="currentTime" class="mb-1"></p>
            <img src="path/to/your/image.jpg" alt="User Image" class="img-fluid mt-3">
        </div>
    </div>

    <!-- Buttons -->
    <div class="row mt-3">
        <div class="col-md-6 offset-md-3 text-center">
            <button type="button" class="btn btn-primary">Button 1</button>
            <button type="button" class="btn btn-secondary ml-2">Button 2</button>
            <button type="button" class="btn btn-success ml-2">Button 3</button>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js (Optional) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- JavaScript for current time and date in Philippines timezone with month, day, year format -->
<script>
    function updateDateTime() {
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
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
