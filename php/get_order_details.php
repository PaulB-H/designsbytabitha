<?php

  include("./session_start.php");

  if(!$_SESSION["user"]){
    echo JSON_encode("You must be logged in to view your orders");
  } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    include ("./config_ordersDB.php");

    $email = $_SESSION["email"];

    $return_arr = array();
    $orderNum = $data;
    
    $query = " SELECT * FROM `order_items` WHERE OrderNum = '$orderNum' AND Email = '$email' " ;
    $result = mysqli_query($con,$query);

    while($row = mysqli_fetch_array($result)){
      $item = $row['Item'];
      $size = $row['Size'];
      $qnty = $row['Qnty'];

      $return_arr[] = array(
        "item" => $item,
        "size" => $size,
        "qnty" => $qnty,
      );
    }

    echo json_encode($return_arr);
    
  }

?>