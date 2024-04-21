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

  try {
    $stmt = $pdo->prepare("UPDATE order_items SET Made = :made WHERE OrderNum = :orderNum AND Item = :item AND Size = :size");
    $stmt->bindParam(':made', $data[3], PDO::PARAM_INT);
    $stmt->bindParam(':orderNum', $data[0], PDO::PARAM_INT);
    $stmt->bindParam(':item', $data[1], PDO::PARAM_STR);
    $stmt->bindParam(':size', $data[2], PDO::PARAM_STR);

    if ($stmt->execute()) {
      $result = "Updated Order " . $data[0] . " Set Item " . $data[1] . $data[2] . " Items Made to " . $data[3] . " ";
    } else {
      // $result = "SQL Err: " . implode(", ", $stmt->errorInfo());
      $result = "SQL Err";
    }
  } catch (PDOException $e) {
    // $result = "PDO Err: " . $e->getMessage();
    $result = "error: DB Error";
  }

  echo(json_encode($result));

};

?>