<?php

include("./session_start.php");

if (!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin") {
  http_response_code(403); 
  echo json_encode(["error" => "No Access"]);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include("../../pdo_config.php");

  // Incoming - integer - ordernum
  $json = file_get_contents('php://input');
  $data = json_decode($json);

  // is_int()
  // is_float
  // is_numeric()

  if (!is_int($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Integers Only']);
    exit();
  }

  try {
    $pdo->beginTransaction();

    $query1 = "DELETE FROM order_items WHERE OrderNum = :ordernum";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':ordernum', $data, PDO::PARAM_INT);
    $stmt1->execute();

    $query2 = "DELETE FROM orders WHERE OrderNum = :ordernum";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(':ordernum', $data, PDO::PARAM_INT);
    $stmt2->execute();

    $pdo->commit();
  } catch (PDOException $e) {
    $pdo->rollBack();
    
    http_response_code(500);
    echo json_encode(['error' => 'DB Error']);
    exit();
  }

  echo(json_encode("Deleted order!"));
}
?>
