<?php

date_default_timezone_set('America/Toronto');

include("./session_start.php");

if(!isset($_SESSION["user"]) || !isset($_SESSION["email"]) ){
  http_response_code(403); 
  echo json_encode("You must be logged in to view your orders");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // INCOMING DATA - JS Array of Arrays
  // [["FabricName", "Size", quantityInt], ["FabricName", "Size", quantityInt]];

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  include("../../config.php");

  $email = $_SESSION["email"];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode("Invalid email format");
    exit; 
  }

  // Check if each item in the order exists in the mask_inventory table
  foreach ($data as $item) {
    $name = $item[0];
    $size = $item[1];
    $qnty = $item[2];

    $checkStmt = $con->prepare("SELECT COUNT(*) FROM mask_inventory WHERE FabricName = ?");
    $checkStmt->bind_param("s", $name);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count == 0) {
      http_response_code(400);
      echo json_encode("Item '$name' does not exist in the inventory. Order not accepted.");
      exit;
    }

    if ($size !== "small" &&  $size !== "medium" && $size !== "large") {
      http_response_code(400);
      echo json_encode("Size does not exist in the inventory. Order not accepted.");
      exit;
    }

    if ($qnty <= 0 || $qnty > 99) {
      http_response_code(400);
      echo json_encode("Cannot order less than 0 or greater than 99 items");
      exit;
    }

    if (!is_int($qnty)) {
      http_response_code(400);
      echo json_encode(['error' => 'Integers Only']);
      exit();
    }
  }

  // Insert the order into the orders table
  $stmt = $con->prepare("INSERT INTO orders (Email) VALUES (?)");
  $stmt->bind_param("s", $email);

  $lastId;
  if ($stmt->execute()) {
    $lastId = $stmt->insert_id;
  } else {
    $success = $stmt->error;
  }

  // Insert each item into the order_items table
  $stmt = $con->prepare("INSERT INTO order_items (OrderNum, Email, Item, Size, Qnty) VALUES (?, ?, ?, ?, ?)");

  $insertedItems = 0;
  foreach ($data as $item) {
    $name = $item[0];
    $size = $item[1];
    $qnty = $item[2];

    $stmt->bind_param("isssi", $lastId, $email, $name, $size, $qnty);
    if ($stmt->execute()) {
      $insertedItems++;
    } else {
      $success = $stmt->error;
    }
  }

  $stmt->close();
  $con->close();

  echo json_encode("Placed an order for " . $insertedItems . " items");
}
?>
