<?php

include("./session_start.php");

if(!isset($_SESSION["user"]) || !isset($_SESSION["email"]) ){
  http_response_code(403); 
  echo json_encode("You must be logged in to view your orders");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include ("../../config.php");

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $email = $_SESSION["email"];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode("Invalid email format");
    exit; 
  }

  $orderNum = $data;
  if (!is_int($orderNum)) {
    http_response_code(400);
    echo json_encode(['error' => 'Integers Only']);
    exit();
  }
  
  $query = " SELECT * FROM `order_items` WHERE OrderNum = '$orderNum' AND Email = '$email' " ;
  $result = mysqli_query($con,$query);

  $return_arr = array();
  while($row = mysqli_fetch_array($result)){
    $item = $row['Item'];
    $size = $row['Size'];
    $qnty = $row['Qnty'];

    $query2 = " SELECT `ImgUrl` FROM `mask_inventory` WHERE `FabricName`  = '$item' " ;
    $result2 = mysqli_query($con, $query2);

    $row2 = mysqli_fetch_array($result2);
    $imgUrl = $row2['ImgUrl'];

    $return_arr[] = array(
      "fabric" => $item,
      "size" => $size,
      "qnty" => $qnty,
      "imgUrl" => $imgUrl,
    );
  }

  echo json_encode($return_arr);
  
}

?>