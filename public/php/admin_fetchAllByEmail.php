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

  $email = $data;


  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); 
    echo json_encode(["error" => "Bad email format"]);
    exit();
  }

  try {
    $stmt = $pdo->prepare("SELECT * FROM `orders` WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
  } catch (PDOException $e) {
    // echo json_encode(['error' => $e->getMessage()]);
    echo json_encode(["error" => "DB Error"]);
  }
}
?>