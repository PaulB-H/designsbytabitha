<?php

include("./session_start.php");

if(!isset($_SESSION["roles"]) || $_SESSION["roles"] !== "admin"){
  http_response_code(403); 
  echo(json_encode("No Access"));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include("../../pdo_config.php");

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  if (!is_int($data)) {
    http_response_code(400);
    echo(json_encode(['error' => 'Number only']));
    exit();
  }

  try {
    $query = "SELECT orders.*, order_items.*
              FROM `orders`
              LEFT JOIN `order_items` ON orders.OrderNum = order_items.OrderNum
              WHERE orders.OrderNum = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $data, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orderDetails = null;
    $orderItems = array();

    if (count($rows) === 0) { 
      echo(json_encode(['error' => 'No orders found']));
      exit();
    }

    foreach ($rows as $row) {
      // First row will be the order details
      if ($orderDetails === null) {
          $orderDetails = array(
            'OrderNum' => $row['OrderNum'],
            'Date' => $row['Date'],
            'Email' => $row['Email'],
            'OrderStatus' => $row['OrderStatus'],
            'orderItems' => array(),
          );
      }

      // This creates an "associative array" which is like a dictionary or map,
      // or like a traditional JS object: 
      // { 
      //   'Item': $row['Item'], 
      //   'Size': $row['Size'], 
      //   'Qnty': $row['Qnty'], 
      //   'Made': $row['Made']
      // } 
      $orderItems[] = array(
        'Item' => $row['Item'],
        'Size' => $row['Size'],
        'Qnty' => $row['Qnty'],
        'Made' => $row['Made'],
      );
    }

    $orderDetails['orderItems'] = $orderItems;

    echo json_encode($orderDetails);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "DB Error fetching order details"]);
  }
}

?>
