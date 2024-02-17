<?php

  include("./session_start.php");

  if ($_SESSION["roles"] !== "admin") {
    echo(JSON_encode("No Access"));
  } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("./config_ordersDB.php");

    // Incomming - Single order number
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $return_arr = array();

    $query = "SELECT * FROM `orders` WHERE OrderNum = $data ";
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

    $query = "SELECT * FROM `order_items` WHERE OrderNum = $data ";
    $result = mysqli_query($con,$query);

    while($row = mysqli_fetch_array($result)){
      $item = $row['Item'];
      $size = $row['Size'];
      $qnty = $row['Qnty'];
      $made = $row['Made'];

      $return_arr[] = array(
        "item" => $item,
        "size" => $size,
        "qnty" => $qnty,
        "made" => $made,
      );
    }

    // echo json_encode($data);
    echo json_encode($return_arr);
  }

?>