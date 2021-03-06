<?php
  include("../php/session_start.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="login_test.css" />
    <style>
      .error {
        color: #ff0000;
      }
      button {
        padding: 10px;
        font-size: 150%;
        margin: 10px;
        border: 1px solid darkgray;
        border-radius: 10px;
      }
      #container {
        display: flex;
        justify-content: center;
        min-height: 90vh;
      }
    </style>
  </head>
  <body>
    <div id="container">
      <div
        style="
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        "
      >
        <h1>Temporary Password has been sent!</h1>
        <button
          onclick="location.href = './session.php'"
          style="font-size: 150%"
        >
          Back to session test page
        </button>
      </div>
    </div>
  </body>
</html>
