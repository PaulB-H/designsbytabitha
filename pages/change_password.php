<?php

    include("../php/session_start.php");

    if($_SESSION["user"]===null){
        header('Location: ./session.php');
    }

?>

<!DOCTYPE HTML>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/account.css">
    <style>
        #content {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            background-color: white;
            padding: 25px;
            width: 100%;
        }

        .error {
            color: #FF0000;
            font-size: 125%;
        }

        input,
        button {
            font-size: 125%;
        }


        #submit,
        button {
            border: none;
            background-color: #575084;
            color: white;
            width: 100%;
            max-width: 229px;
            padding: 3px 0;
        }
    </style>
</head>

<body>
  <?php
  
    // define variables and set to empty values
    $passwordErr= "";
    $password = "";
    $validPassword = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (empty($_POST["password"])) {
          $passwordErr = "<br>Password is required<br>";
      } else {
          $password = test_input($_POST["password"]);
          // regex check
          if (!preg_match("/^[a-zA-Z]{5,}[0-9]{1,}[\W\S]*$/",$password)) {
              $passwordErr = "At least 1 number and 5 letters<br>";
          } else { 
              $validPassword = $password;
              $validInputs ++;
          }
      }
    
        if ($validInputs === 1){
            
            include "../php/config.php";

            $email = $_SESSION["email"];
            
            $stmt = $con->prepare("UPDATE users SET Password = ? WHERE Email = ?");
            $stmt->bind_param( "ss", password_hash($validPassword, PASSWORD_BCRYPT), $email);
            
            if ($stmt->execute()) { 
                $statusMessage  = "Password Changed!";
                header('Location: ./session.php');
                
            } else {
                $statusMessage = $stmt->error;
            }
            
        } else {
            $success = "Please fix the errors and try again";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $userName = $password = $email = "";
    $validUserName = $validPassword = $validEmail = "";

  ?>

    <div id="content">
        <h2 style="margin-bottom: 0;">Change Password</h2>

        <p style="margin-bottom: 25px;"><span class="error">* required field</span></p>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <input autocomplete="off" id="password" type="password" name="password" placeholder="New Password:"
                value="<?php echo $password;?>">
            <span class="error"> * </span>
            <br>

            <span class="error">
              <?php echo $passwordErr;?>
            </span>

            <br>

            <button id="togglePassBtn" class="fancyBtn" type="button" onclick="togglePassVisible(); toggleText();">
                Show Password
            </button>

            <br><br>

            <input id="submit" class="fancyBtn" type="submit" name="submit" value="Submit">

        </form>

        <h2 id="errorBox"><?php echo $success; ?></h2>

        <br><br>

        <a href="./session.php" style="width: 100%;">
            <button class="fancyBtn" style="margin-top: 25px">
                ..Back
            </button>
        </a>

    </div>

    <script>

        function togglePassVisible() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function toggleText() {
            var y = document.getElementById("togglePassBtn").innerHTML;
            if (y == "Show Password") {
                document.getElementById("togglePassBtn").innerHTML = "Hide Password"
            } else {
                document.getElementById("togglePassBtn").innerHTML = "Show Password"
            }
        }

        function readErrorMsg() {
            errorMsg = document.getElementById("errorBox").innerHTML;
            var res = errorMsg.split(" ");
            if (res[res.length - 1] == "'UserName'") { document.getElementById("errorBox").innerHTML = "User Name Taken" }
            else if (res[res.length - 1] == "'Email'") { document.getElementById("errorBox").innerHTML = "Email Taken" }
        }
        readErrorMsg();

    </script>

</body>

</html>
