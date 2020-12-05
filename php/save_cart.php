<?php

include ("./session_start.php");

if($_SESSION["user"]===null){
  echo JSON_encode("You must be logged in for this route");
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $json = file_get_contents('php://input');
  $data = json_decode($json);
  $arr = json_decode(json_encode($data), true);
  //Finally working and can access object properties
  // echo JSON_encode($arr['name']);

  $email = $_SESSION["email"];
  $name = $arr['name'];
  $size = $arr['size'];
  $qnty = $arr['qnty'];
  $orderStatus = "Submitted";

  include "./config_ordersDB.php";

  $stmt = $con->prepare("INSERT INTO orders (Email) VALUES (?)");
  $stmt->bind_param("s", $email);
  
  $lastid;
  if ($stmt->execute()) {
    $lastid = $stmt->insert_id;
    // echo JSON_encode($lastid);
  } else {
    $success = $stmt->error;
  }

  $stmt = $con->prepare("INSERT INTO order_items (OrderNum, Item, Size, Qnty) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("issi", $lastid, $name, $size, $qnty);
  if ($stmt->execute()) {
    echo JSON_encode($lastid);
  } else {
    $success = $stmt->error;
  }

  // $stmt->close();
  // $con->close();


  // echo JSON_encode($arr['name']);

  // $validInput = 0;
  // $reqInput = 4;
  // if (empty($arr['name'])) {
  //     $nameErr = "<br>Name Required<br>";
  // } else {
  //     $name = test_input($arr["name"]);
  //     // regex [selectGrp]{min,max}/global /caseInsensitive
  //     if (!preg_match("/[a-z0-9 -]{0,30}/gi", $name)) {
  //         $nameErr = "One word, only letters<br>";
  //     } else {
  //         $validName = $name;
  //         $validInput ++;
  //     }
  // }

  // if (empty($arr["size"])) {
  //     $sizeErr = "<br>Size Required<br>";
  // } else {
  //     $size = test_input($arr["size"]);
  //     // regex [selectGrp]{min,max}/global /caseInsensitive
  //     if (!preg_match("/[a-z0-9]{0,30}/gi", $size)) {
  //         $sizeErr = "One word, only letters<br>";
  //     } else {
  //         $validSize = $size;
  //         $validInput ++;
  //     }
  // }

  // if (empty($arr["qnty"])) {
  //     $qntyErr = "<br>Qnty Required<br>";
  // } else {
  //     $qnty = test_input($arr["qnty"]);
  //     // regex [selectGrp]{min,max}/global /caseInsensitive
  //     if (!preg_match("/[0-9]{0,5}/gi", $qnty)) {
  //         $qntyErr = "0-9 ONLY, MAX 5 CHAR<br>";
  //     } else {
  //         $validQnty = $qnty;
  //         $validInput ++;
  //     }
  // }

  // if($validInput === $reqInput){
      
  // }

}
?>