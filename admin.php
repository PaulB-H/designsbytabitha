<?php

    include("./session_start.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if($_SESSION["user"] !== "Paulus" && $_SESSION["user"] !== "Tabgbernard"){
            echo(JSON_encode("Nuh-uh"));
        } else {

            include("./config_ordersDB.php");

            $return_arr = array();
            $email = $_SESSION["email"];
            
            $query = "SELECT * FROM orders";
            $result = mysqli_query($con,$query);

            while($row = mysqli_fetch_array($result)){
                $orderNum = $row['OrderNum'];
                $Date = $row['Date'];
                $Email = $row['Email'];
                $orderStatus = $row['OrderStatus'];

                $return_arr[] = array(
                                "orderNum" => $orderNum,
                                "Date" => $Date,
                                "Email" => $Email,
                                "orderStatus" => $orderStatus,
                                );
            }
            echo json_encode($return_arr);
        }
        
    }

?>