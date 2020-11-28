<?php
date_default_timezone_set('America/Toronto');

include ("./session_start.php");

if($_SESSION["user"]===null){
    echo JSON_encode("You must be logged in to checkout");
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // INCOMING DATA - JS Array of Arrays
    // [["FabricName", "Size", quantityInt], ["FabricName", "Size", quantityInt]];

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    include ("./config_ordersDB.php");

    $email = $_SESSION["email"];
    
    $stmt = $con->prepare("INSERT INTO orders (Email) VALUES (?)");
    $stmt->bind_param("s", $email);

    $lastid;
    if ($stmt->execute()) {
        $lastid = $stmt->insert_id;
    } else {
        $success = $stmt->error;
    }

    $stmt = $con->prepare("INSERT INTO order_items (OrderNum, Email, Item, Size, Qnty) VALUES (?, ?, ?, ?, ?)");

    $insertedItems;
    $len =  count($data);
    for ($x = 0; $x <= $len-1; $x++) {
        $name = $data[$x][0];
        $size = $data[$x][1];
        $qnty = $data[$x][2];
        $stmt->bind_param("isssi", $lastid, $email, $name, $size, $qnty);
        if ($stmt->execute()) {
            $insertedItems ++;
        } else {
            $success = $stmt->error;
        }
    } 
    
    $stmt -> close();
    $con -> close();

    echo JSON_encode("Placed an order for " . $insertedItems . " items");
}
?>