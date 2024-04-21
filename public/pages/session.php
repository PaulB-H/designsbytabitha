<?php
  include("../php/session_start.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account</title>
    <link rel="stylesheet" type="text/css" href="../styles/account.css" />

    <style>
      * {
        margin: 0px;
        padding: 0px;
        box-sizing: border-box;
      }

      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      main {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: white;
        width: 100%;
      }

      button {
        padding: 5px 20px;
        font-size: 125%;
        border: none;
        width: 100%;
        background-color: #575084;
        color: white;
        border-radius: 5px;
      }

      #account-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 10px 10px;
        grid-template-areas: ' . . ' ' . . ';
      }

      #account-buttons a {
        margin: 10px;
      }
    </style>
  </head>

  <body>
    <main>
      <h1>Designs by Tabitha</h1>
      <br />

      <h3>Signed in as:</h3>
      <p style="font-size: 20px; font-family: sans-serif; margin: 10px;">
        <?php
          if (empty($_SESSION) || empty($_SESSION["user"])) {
            echo "Not signed in";
          } else {
            echo $_SESSION["user"];
          }
        ?>
      </p>

      <br />

      <div id="account-buttons">
        <?php if (empty($_SESSION["user"])): ?>

          <a href="./sign_in.php">
            <button>Log In</button>
          </a>

          <a href="./sign_up.php">
            <button>Sign Up</button>
          </a>

          <a href="./reset_password.php">
            <button>Pass Reset</button>
          </a>

          <a href="./mask_page.php">
            <button>
              Back to Site
            </button>
          </a>

        <?php endif; ?>

        <?php if (!empty($_SESSION["user"])): ?>

          <a href="./my_orders.php">
            <button>
              View Orders
            </button>
          </a>

          <a href="./profile.php">
            <button>
              Profile
            </button>
          </a>

          <a href="#">
            <button onclick="sessionDestroy()">Logout</button>
          </a>

          <a href="./change_password.php">
            <button>
              Pass Change
            </button>
          </a>

          <a href="./mask_page.php">
            <button>Shop</button>
          </a>
          <?php if (isset($_SESSION["roles"]) && $_SESSION["roles"] === "admin"): ?>
            <a href="./admin.php">
              <button>
                Admin
              </button>
            </a>
          <?php endif; ?>

        <?php endif; ?> 

      </div>
    </main>

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
            // sessionStatus();
            // getUser();
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
    </script>
  </body>
</html>
