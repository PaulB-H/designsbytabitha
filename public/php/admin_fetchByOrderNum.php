<?php

include("./session_start.php");

if(!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin"){
  http_response_code(403); 
  echo(json_encode("No Access"));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include("../../pdo_config.php");

  // Incoming - Single email address
  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $orderNum = $data;

  if (!ctype_digit($orderNum)) {
    http_response_code(400);
    echo(json_encode(['error' => 'Number only']));
    exit();
  }

  try {
    $stmt = $pdo->prepare("SELECT * FROM `orders` WHERE OrderNum = :orderNum");
    $stmt->bindParam(':orderNum', $orderNum);
    
    $stmt->execute();

    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // $results = $stmt->fetchAll(PDO::FETCH_OBJ); // Need to do just fetch to get single obj
    $results = $stmt->fetch(PDO::FETCH_OBJ);

    if (empty($results)) {
      echo json_encode(["error" => "No order found!"]);
      exit();
    }

    echo json_encode($results);
  } catch (PDOException $e) {
    // echo json_encode(['error' => $e->getMessage()]);
    echo json_encode(["error" => "DB Error"]);
  }
}
?>