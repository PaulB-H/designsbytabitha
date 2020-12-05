<?php

include "./config.php";

$return_arr = array();

$query = "SELECT * FROM mask_inventory ORDER BY FabricName ASC";

$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)){
  $MaskId = $row['MaskId'];
  $FabricName = $row['FabricName'];
  $ImgUrl = $row['ImgUrl'];
  $Size = $row['Size'];
  $Stock = $row['Stock'];

  $return_arr[] = array(
    "MaskId" => $MaskId,
    "FabricName" => $FabricName,
    "ImgUrl" => $ImgUrl,
    "Size" => $Size,
    "Stock" => $Stock
  );
}

// Encoding array in JSON format
echo json_encode($return_arr);

?>