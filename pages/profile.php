<?php

  include("../php/session_start.php");

  $error = "";
  if ($_SESSION["user"] == ""){
      $error = "<div style='test-align: center;'>You are not signed in!!</div>";
  } else {
  
  }
    
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../styles/account.css" />
    <style>
      .error {
        color: #ff0000;
      }

      #container {
        background-color: white;
        padding: 25px;
        width: 100%;
      }

      button {
        border: none;
        font-size: 125%;
        width: 100%;
        max-width: 229px;
        margin: 10px;
        background-color: #575084;
        color: white;
      }

      a {
        display: flex;
        justify-content: center;
        width: 100%;
        text-decoration: none;
      }
    </style>
  </head>

  <body>
    <div
      id="container"
      style="
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      "
    >
      <h1><?php echo $error; ?></h1>

      <?php
        if($error != ""){
            echo "<div style='display:none;'>"; } ?>

      <h3>
        Logged in as:
        <?php echo $_SESSION["user"]; ?>
      </h3>

      <p>
        Email:
        <?php echo $_SESSION["email"]; ?>
      </p>

      <br />
      <a href="#"> <!-- ./update_email.html -->
        <button class="fancyBtn"><strike>Change Email</strike></button>
      </a>

      <br /><br />

      <?php
        if($error != ""){
          echo "</div>"; } ?>

      <a href="./session.php">
        <button class="fancyBtn">..Back</button>
      </a>
    </div>
  </body>
</html>
