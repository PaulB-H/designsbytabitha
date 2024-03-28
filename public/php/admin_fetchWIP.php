<?php

  include("./session_start.php");

  if ($_SESSION["roles"] !== "admin") {
    echo(JSON_encode("No Access"));
  } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include("../../config.php");

    $return_arr = array();

    // Use prepared statements
    $query = "SELECT * FROM orders WHERE OrderStatus = 'WIP'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // Fetch all rows as associative arrays
    $return_arr = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Echo the result as JSON
    echo json_encode($return_arr);

    // Close the statement
    $stmt->close();
    $con->close();
  }

?>