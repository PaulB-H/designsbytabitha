<?php
  include("../php/session_start.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../styles/account.css" />

    <style>
      * {
        margin: 0px;
        padding: 0px;
        /* color: black; */
        /* word-wrap:normal; */
      }

      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .hide {
        display: none;
      }

      button {
        padding: 3px 0;
        font-size: 125%;
        margin: 10px;
        border: none;
        width: 100%;
        background-color: #575084;
        color: white;
        border-radius: 5px;
      }

      #container {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: white;
        width: 100%;
      }

      input {
        text-align: center;
        width: 100%;
      }
    </style>
  </head>

  <body>
    <div id="container">
      <h1>Designs by Tabitha</h1>
      <br />
      <h3>Signed in as:</h3>
      <p>
        <?php
          if($_SESSION["user"]===""||$_SESSION["user"]===undefined||$_SESSION["user"]===null){
            echo "...Not signed in";
          } else {
            echo $_SESSION["user"];
          }
        ?>
      </p>

      <br />

      <div
        class="grid-container"
        style="
          display: grid;
          grid-template-columns: 1fr 1fr;
          grid-template-rows: 1fr 1fr;
          gap: 10px 10px;
          grid-template-areas: ' . . ' ' . . ';
        "
      >
        <?php
          if($_SESSION["user"] != null){
            echo '<div style="display: none;">'; 
          } 
        ?>

        <button onclick=" window.location.href='sign_in.php'">Log In</button>

        <button onclick=" window.location.href='sign_up.php'">Sign Up</button>

        <button onclick=" window.location.href='reset_password.php'">
          Pass Reset
        </button>

        <button onclick=" window.location.href='mask_page.php'">
          Back to Site
        </button>

        <?php
          if( $_SESSION["user"] != null ){
            echo '</div>'; 
          } 
        ?>

        <?php
          if( $_SESSION["user"] === null ){
            echo '<div style="display: none;">'; 
          } 
        ?>
        
        <button onclick=" window.location.href='./my_orders.php'">
          View Orders
        </button>

        <button onclick=" window.location.href='./profile.php'">
          Profile
        </button>

        <button onclick=" sessionDestroy()">Logout</button>

        <button onclick="window.location.href='./change_password.php'">
          Pass Change
        </button>

        <button onclick="window.location.href='./mask_page.php'">Shop</button>

        <?php
          if($_SESSION["roles"] !== "admin"){
            echo '<div style="display: none">'; } ?>

        <button
          onclick=" window.location.href='./admin.php'"
        >
          Admin
        </button>

        <?php
          if($_SESSION["roles"] !== "admin"){
            echo '</div>'; } ?>

        <?php
          if($_SESSION["user"] === null){
            echo '</div>'; 
          } 
        ?>
      </div>
    </div>

    <script>
      function sessionStatus() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);

            //document.getElementById("session-id-container").innerHTML = myObj.sessID;
            //document.getElementById("session-status-container").innerHTML = myObj.sessStatus;

            console.log(myObj);
          }
        };
        xhttp.open("GET", "../php/session_status.php", true);
        xhttp.withCredentials = true;
        xhttp.send();
      }

      function sessionDestroy() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            sessionStatus();
            getUser();
            window.location.href =
              "./session.php";
          }
        };
        xhttp.open("GET", "../php/session_destroy.php", true);
        xhttp.withCredentials = true;
        xhttp.send();
      }

      function getUser() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            if (this.responseText != "") {
              document.getElementById(
                "userSignedIn"
              ).innerHTML = this.responseText;
            } else {
              document.getElementById("userSignedIn").innerHTML =
                "Not signed in";
            }
          }
        };
        xhttp.open("GET", "../php/get_user.php", true);
        xhttp.withCredentials = true;
        xhttp.send();
      }

      // getUser();
      // sessionStatus();
    </script>
  </body>
</html>
