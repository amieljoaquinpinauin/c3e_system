<!-- navbar.php -->
<!-- navbar.php -->
<?php
// Define an array with page names and their corresponding URLs
$pages = [
    'Daily Time Record' => 'index.php',
    'File for Leave' => 'leave_list.php',
    'Supplies' => 'supplies.php',
];

// Get the current page name from the URL
$current_page = basename($_SERVER['PHP_SELF']);

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
   <img id="logo_c3e" src="img/c3e_logo.png" alt="nav form" style="height: 50px; width: 65px;" />
   <a class="navbar-brand" href="#"><b>C3E IT Services</b></a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
   </button>

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
         <?php
         // Loop through the pages array to create navigation links
         foreach ($pages as $page_name => $page_url) {
            $active_class = ($current_page === $page_url) ? 'active' : '';
            echo '<li class="nav-item ' . $active_class . '">';
            echo '<a class="nav-link" href="' . $page_url . '">' . $page_name;
            echo ($active_class === 'active') ? '<span class="sr-only">(current)</span>' : '';
            echo '</a></li>';
         }
         ?>
      </ul>
      <form class="form-inline my-2 my-lg-0">
         <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
         <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
   </div>
</nav>
