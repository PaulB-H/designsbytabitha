<?php

  include("./session_start.php");

  if ($_SESSION["roles"] !== "admin") {
    echo(JSON_encode("No Access"));
  } else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    include("./config_ordersDB.php");

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $stmt = $con->prepare("UPDATE orders SET OrderStatus = ? WHERE OrderNum = ?");
    $stmt->bind_param("si", $data[1], $data[0]);

    if ($stmt->execute()) {
      $result = "Updated Order " . $data[0] . " Status to " . $data[1] . " ";
    } else {
      $result = "SQL Err: " . $success . " ";
      $success = $stmt->error;
    }

    $stmt -> close();
    $con -> close();

    echo(JSON_encode($result));

  }
?>