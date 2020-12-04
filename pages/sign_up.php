<?php
    include("../php/session_start.php")
?>

<!DOCTYPE HTML>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-control" content="public, max-age=3600" />
  <link rel="stylesheet" type="text/css" href="../styles/account.css">
  <style>
    #content {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        flex-direction: column;
        width: 100%;
        background-color: white;
        padding: 25px;
    }

    #sign-up-form {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;

    }

    .error {
      color: #FF0000;
      font-size: 125%;
      max-width: max-content;
      margin: auto;
      padding: 3px;
    }

    #errorBox {
      background-color: yellow;
    }

    input,
    button {
      font-size: 125%;
    }

    #submit,
    button {
      margin: 10px 0;
      /* border-radius: 10px; */
      border: none;
      width: 100%;
    }
  </style>
</head>

<body>

  <?php

    // define variables and set to empty values
    $userNameErr = $passwordErr = $emailErr = "";
    $userName = $password = $email = "";
    $validUserName = $validPassword = $validEmail = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (empty($_POST["userName"])) {
        $userNameErr = "User Name is required<br>";
      } else {
        $userName = test_input($_POST["userName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z]*$/",$userName)) {
          $userNameErr = "One word, only letters<br>";
        } else {
          $validUserName = $userName;
          $myVar ++;
        }
      }
  
      if (empty($_POST["password"])) {
        $passwordErr = "Password is required<br>";
      } else {
        $password = test_input($_POST["password"]);
        // regex check
        if (!preg_match("/^[a-zA-Z]{5,}[0-9]{1,}[\W\S]*$/",$password)) {
          $passwordErr = "At least 1 number and 5 letters<br>";
        } else { 
          $validPassword = $password;
          $myVar ++;
        }
      }
  
      if (empty($_POST["email"])) {
        $emailErr = "Email is required<br>";
      } else {
        $email = test_input($_POST["email"]);
        // Check if email is correct format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid email format";
        } else { 
          $validEmail = $email;
          $myVar ++;
        }
      }
  
      if ($myVar === 3){
        $myVar ++;
        
        include "../php/config_ordersDB.php";
        $userStr = "user";
        $stmt = $con->prepare("INSERT INTO users (UserName, Password, Email, Roles) VALUES (?, ?, ?, ?)");
        $stmt->bind_param( "ssss", $validUserName , password_hash($validPassword, PASSWORD_BCRYPT), $validEmail, $userStr);
        
        if ($stmt->execute()) { 
          $success = "User Registered Successfully";
          $_SESSION["user"] = $validUserName;
          $_SESSION["email"] = $validEmail;
          header('Location: ./sign_up_success.php');
        } else {
          $success = $stmt->error;
        }
        
        $con->close();
          
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
    <h1>Designs by Tabitha</h1>
    <h2 style="margin-bottom: 0; font-family: sans-serif;">Sign Up Form</h2>
    <p><span class="error">* required field</span></p>

    <form id="sign-up-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div>
        <input type="text" name="userName" placeholder="User Name:" value="<?php echo $userName;?>">
        <span class="error"> * </span>
      </div>
      <span class="error"><?php echo $userNameErr;?></span>

      <div>
        <input autocomplete="off" id="password" type="password" name="password" placeholder="Password:"
          value="<?php echo $password;?>" style="margin-top: 10px">
        <span class="error"> *</span>
      </div>
      <span class="error"><?php echo $passwordErr;?></span>

      <button id="togglePassBtn" class="fancyBtn" type="button" onclick="togglePassVisible(); toggleText();">
        Show Password
      </button>

      <div>
        <input type="text" name="email" placeholder="Email:" value="<?php echo $email;?>">
        <span class="error"> *</span>
      </div>
      <span class="error"><?php echo $emailErr;?></span>

      <input id="submit" class="fancyBtn" type="submit" name="submit" value="Submit">

    </form>

    <h2 id="errorBox"><?php echo $success; ?></h2>

    <a href="./session.php" style="width: 100%;">
      <button class="fancyBtn" style=" font-size: 125%; max-width: 229px;">
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