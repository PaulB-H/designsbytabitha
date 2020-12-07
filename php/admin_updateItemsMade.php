<?php

  include("./session_start.php");

  if ($_SESSION["roles"] !== "admin") {
    echo(JSON_encode("No Access"));
  } else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    include("./config_ordersDB.php");

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $stmt = $con->prepare("UPDATE order_items SET Made = ? WHERE OrderNum = ? AND Item = ?");
    $stmt->bind_param("iis", $data[2], $data[0], $data[1]);

    if ($stmt->execute()) {
      $result = "Updated Order " . $data[0] . " Set Item " . $data[1] . " Items Made to " . $data[2] . " ";
    } else {
      $result = "SQL Err: " . $success . " ";
      $success = $stmt->error;
    };

    $stmt -> close();
    $con -> close();

    echo(JSON_encode($result));

  };

?>