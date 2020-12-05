<?php

  include("./session_start.php");
  
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if($_SESSION["roles"] !== "admin"){
      echo(JSON_encode("No Access"));
    } else {
      include("./config_ordersDB.php");

      $return_arr = array();
      
      $query = "SELECT * FROM orders WHERE OrderStatus = 'Pending' ";
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
    }
      
  }

?>