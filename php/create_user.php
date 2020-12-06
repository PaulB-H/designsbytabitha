<?php

if ($_SESSION["roles"] !== "admin") {
  echo(JSON_encode("No Access"));
} else {

  include "./config.php";

  $return_arr = array();

  $query = "SELECT * FROM users";

  $result = mysqli_query($con, $query);

  while($row = mysqli_fetch_array($result)){
    $UserNum = $row['UserNum'];
    $UserName = $row['UserName'];
    $Password = $row['Password'];
    $Email = $row['Email'];

    $return_arr[] = array(
      "UserNum" => $UserNum,
      "UserName" => $UserName,
      "Password" => $Password,
      "Email" => $Email,
    );
  }

  // Encoding array in JSON format
  echo json_encode($return_arr);
}

?>