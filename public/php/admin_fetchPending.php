<?php

  include("./session_start.php");

  if (!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin") {
    http_response_code(403); 
    echo json_encode(["error" => "No Access"]);
    exit();
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include("../../config.php");

    $return_arr = array();

    // Use prepared statements
    $query = "SELECT * FROM orders WHERE OrderStatus = 'Pending'";
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