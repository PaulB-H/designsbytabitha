<?php

include("./session_start.php");

if(!isset($_SESSION["user"]) || !isset($_SESSION["email"]) ){
  http_response_code(403); 
  echo json_encode("You must be logged in to view your orders");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  include("../../pdo_config.php");

  $return_arr = array();

  $email = $_SESSION["email"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode("Invalid email format");
    exit; 
  }

  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  try {

    $query = "SELECT * FROM orders WHERE Email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $return_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($return_arr);
  } catch (PDOException $e) {
    // echo json_encode("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'DB Error']);
  }
}

?>
