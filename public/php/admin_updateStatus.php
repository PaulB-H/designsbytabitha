<?php

include("./session_start.php");

if(!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin"){
  http_response_code(403); 
  echo(json_encode("No Access"));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
  include("../../pdo_config.php");

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $orderNum = $data[0];
  $status = $data[1];

  if (!is_int($orderNum)) {
    http_response_code(400);
    echo json_encode(['error' => 'Order Num Integers Only']);
    exit();
  }

  if (!in_array($status, ["Pending", "WIP", "Canceled", "Complete"])) {
    http_response_code(400); 
    echo json_encode(['error' => 'Bad status, valid: Pending, WIP, Canceled, Complete']);
    exit();
  }

  try {
    $stmt = $pdo->prepare("UPDATE orders SET OrderStatus = :status WHERE OrderNum = :orderNum");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':orderNum', $orderNum);
    $stmt->execute();

    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
      echo json_encode("Order deleted");
    } else {
      http_response_code(404); 
      echo json_encode(['error' => 'No order found!']);
    }
  } catch (PDOException $e) {
    // echo "Error: " . $e->getMessage();
    http_response_code(500); 
    echo json_encode(['error' => 'DB Error']);
  }

}

?>