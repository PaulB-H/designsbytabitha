<?php

    include("../php/session_start.php");
    
    if($_SESSION["user"] != ""){
        $to = "p.bernardhall@gmail.com";
        $subject = "New User";
        $txt = "New user signed up!";
        $headers = "From: tabitha@designsbytabitha.ca";
        mail($to,$subject,$txt,$headers);
    }
    
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../styles/account.css" />
    <style>
      .error {
        color: #ff0000;
      }
    </style>
  </head>
  <body>
    <div
      style="
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: white;
        padding: 10px;
        border-radius: 5px;
      "
    >
      <h1>Sign-up Successfull!</h1>
      <h3>
        Welcome
        <?php echo $_SESSION["user"]; ?>
      </h3>
      <br /><br />
      <button
        onclick="location.href = './session.php';"
        style="font-size: 150%"
      >
        Back to Account page
      </button>
    </div>
  </body>
</html>
