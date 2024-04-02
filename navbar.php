<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sidebar Navigation</title>
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
   <style>
      /* Sidebar style */
      .sidebar {
         position: fixed;
         top: 0;
         left: 0;
         height: 100%;
         width: 250px;
         background-color: #343a48; /* Dark background color */
         padding-top: 70px; /* Adjusted for the logo and brand */
         color: #fff; /* Text color */
      }

      .sidebar-brand {
         padding: 10px 20px;
         font-size: 1.5rem;
         color: #fff;
         text-decoration: none;
      }

      .sidebar-brand img {
         width: 50px; /* Adjust the logo size as needed */
         height: auto;
         margin-right: 10px;
      }

      .sidebar-nav-link {
         padding: 10px 20px;
         font-size: 1rem;
         color: #fff;
         text-decoration: none;
         transition: background-color 0.3s;
      }

      .sidebar-nav-link:hover {
         background-color: #495057; /* Darker background color on hover */
      }

      /* Navbar style */
      .navbar {
         z-index: 1; /* Ensure navbar stays on top of sidebar */
         position: fixed;
         width: 250px;
         background-color: #fff;
      }
   </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
   <a class="sidebar-brand" href="#">
      <img id="logo_c3e" src="img/c3e_logo.png" alt="nav form"> C3E IT Services
   </a>
   <ul class="nav flex-column">
      <?php
      // Define an array with page names and their corresponding URLs
      $pages = [
          'Daily Time Record' => 'index.php',
          'File for Leave' => 'leave_list.php',
          'Supplies' => 'supplies.php',
      ];

      // Get the current page name from the URL
      $current_page = basename($_SERVER['PHP_SELF']);

      // Loop through the pages array to create sidebar links
      foreach ($pages as $page_name => $page_url) {
         $active_class = ($current_page === $page_url) ? 'active' : '';
         echo '<li class="nav-item">';
         echo '<a class="nav-link sidebar-nav-link ' . $active_class . '" href="' . $page_url . '">' . $page_name . '</a>';
         echo '</li>';
      }
      ?>
   </ul>
</div>
</body>
</html>
