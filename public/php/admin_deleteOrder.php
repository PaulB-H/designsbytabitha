<?php

  include("./session_start.php");

  if ($_SESSION["roles"] !== "admin") {
    echo(JSON_encode("No Access"));
  } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("./config_ordersDB.php");

    // Incoming - integer - ordernum
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $query = "DELETE FROM order_items WHERE OrderNum = '$data' ";
    $result = mysqli_query($con,$query);

    $query = "DELETE FROM orders WHERE OrderNum = '$data'";
    $result = mysqli_query($con, $query);

    $con -> close();

    echo(JSON_encode("Deleted order!"));

  }
?>