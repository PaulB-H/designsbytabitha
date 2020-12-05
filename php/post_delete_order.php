<?php

include ("./session_start.php");

if($_SESSION["user"]===null){
  echo JSON_encode("You must be logged in");
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  include ("./config_ordersDB.php");

  $return_arr = array();
  $orderNum = $data;
  $email = $_SESSION['email'];
  
  $query = "DELETE FROM order_items WHERE OrderNum = '$orderNum' AND Email = '$email' ";
  $result = mysqli_query($con,$query);

  $query = "DELETE FROM orders WHERE OrderNum = '$orderNum' AND Email = '$email' ";
  $result = mysqli_query($con,$query);

  echo json_encode("Deleted Order");
}
?>