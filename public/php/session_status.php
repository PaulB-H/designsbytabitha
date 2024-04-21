<?php 

if (isset($_COOKIE[session_name()])) {
  session_start();
  $sessID = session_id();
}

if (session_status() === 0){
  $sessStatus = "Sessions disabled (Response: 0)";
} else if (session_status() === 1){
  $sessStatus = "Sessions enabled, but none exist (Response: 1)";
} else if (session_status() === 2){
  $sessStatus = "Sessions enabled, and one exists (Response: 2)";
};

$response = array("sessID"=>$sessID, "sessStatus"=>$sessStatus);

echo json_encode($response);
 
?>