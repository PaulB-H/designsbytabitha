<?php
  include("./session_start.php");

  include("./config_ordersDB.php");

  $return_arr = array();
  $email = $_SESSION["email"];
  
  $query = "SELECT * FROM orders WHERE Email = '$email' ";
  $result = mysqli_query($con,$query);

  while($row = mysqli_fetch_array($result)){
    $orderNum = $row['OrderNum'];
    $Date = $row['Date'];
    $Email = $row['Email'];
    $orderStatus = $row['OrderStatus'];

    $return_arr[] = array(
      "orderNum" => $orderNum,
      "Date" => $Date,
      "Email" => $Email,
      "orderStatus" => $orderStatus,
    );
  }

  echo json_encode($return_arr);

?>