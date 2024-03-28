<?php
include("./session_start.php");

if (!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin") {
  http_response_code(403); 
  echo json_encode(["error" => "No Access"]);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  include("../../pdo_config.php");

  try {
    $stmt = $pdo->query("SELECT * FROM orders");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error']);
    exit();
  }
}
?>