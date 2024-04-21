<?php

include("./session_start.php");

if(!isset($_SESSION["user"]) || !isset($_SESSION["email"]) ){
  http_response_code(403); 
  echo json_encode("You must be logged in to view your orders");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include("../../config.php");

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $orderNum = $data;
  if (!is_int($orderNum)) {
    http_response_code(400);
    echo json_encode(['error' => 'Integers Only']);
    exit();
  }

  $email = $_SESSION['email'];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode("Invalid email format");
    exit; 
  }

  $count = 0;
  // Check if an order exists that matches the user
  // If user is admin, skip this check
  if ($_SESSION["roles"] !== "admin") {
    $checkStmt = $con->prepare("SELECT COUNT(*) FROM orders WHERE OrderNum = ? AND Email = ?");
    $checkStmt->bind_param("is", $orderNum, $email);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
  }

  if ($count > 0 || $_SESSION["roles"] === "admin") {
    // Order exists and belongs to user OR user is admin, try to delete
    $deleteOrderItemsStmt = $con->prepare("DELETE FROM order_items WHERE OrderNum = ? AND Email = ?");
    $deleteOrderItemsStmt->bind_param("is", $orderNum, $email);
    $deleteOrderItemsStmt->execute();
    $deleteOrderItemsStmt->close();

    $deleteOrdersStmt = $con->prepare("DELETE FROM orders WHERE OrderNum = ? AND Email = ?");
    $deleteOrdersStmt->bind_param("is", $orderNum, $email);
    $deleteOrdersStmt->execute();
    $deleteOrdersStmt->close();

    echo json_encode("Deleted Order");
  } else {
    echo json_encode("Order not found or you don't have permission to delete it.");
    http_response_code(404);
  }

  $con->close();
}

?>
