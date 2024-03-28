<?php

include("./session_start.php");

if(!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin"){
  http_response_code(403); 
  echo(json_encode("No Access"));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  include("../../pdo_config.php");

  try {
    $query = "SELECT * FROM orders WHERE OrderStatus = 'Complete'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error']);
  }
}

?>