<?php
  include("../php/session_start.php");
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-control" content="public, max-age=3600" />
    <link rel="stylesheet" type="text/css" href="../styles/account.css">
    <style>
        #content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background: white;
            padding: 10px;
            width: 100%;
        }

        #submitBtn {
            font-size: 125%;
            width: 100%;
            border: none;
        }

        button {
            width: 100%;
            border: none;
            font-size: 125%;
            margin-top: 10px;
            max-width: 268.35px;
        }

        h3,
        h2 {
            font-family: sans-serif;
        }
    </style>
</head>

<body>

<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    if (empty($_POST["email"])) {
        $emailErr = "<br>Email is required<br>";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else { 
            $validEmail = $email;
            $myVar ++;
        }
    }
  
    if ($myVar === 1){
        
      include "../php/config.php";
      
      $stmt = $con->prepare("SELECT UserName FROM users WHERE Email = ?");
      $stmt->bind_param( "s", $validEmail);
      $stmt->execute();
      $stmt -> store_result(); 
      $stmt -> bind_result($match); 
      $stmt -> fetch();

      if ($match == ""){
        $validUser = false;
        $statusMessage = "No user found";
      } else {
        $validUser = true;
      }
      
      if ($validUser){
      
        //Generate password
        //Hash password
        //password_hash($validPassword, PASSWORD_BCRYPT)
    
        $tempPass = "";
        
        $upper = "ABCDEFGHJKMNPQRSTUVWXYZ"; // 22 char long
        $lower = "abcdefghjkmnpqrstuvwxyz"; // 22 char long
        $nums = "123456789"; // 8 char long
        
        $tempPass .= $upper[rand(0,22)];
        $tempPass .= $lower[rand(0,22)];
        $tempPass .= $lower[rand(0,22)];
        $tempPass .= $nums[rand(0,8)];
        $tempPass .= $upper[rand(0,22)];
        $tempPass .= $lower[rand(0,22)];
        $tempPass .= $lower[rand(0,22)];
        $tempPass .= $nums[rand(0,8)];
        
        $stmt = $con->prepare("UPDATE users SET TempPass = ? WHERE Email = ?");
        $stmt->bind_param( "ss", password_hash($tempPass, PASSWORD_BCRYPT), $validEmail);
        
        if ($stmt->execute()) { 
          $statusMessage  = "temppass emailed";
          
          $to = $validEmail;
          $subject = "Password Reset - Designs by Tabitha";
          $message = "Your temp password for designsbytabitha.ca is: {$tempPass}";
          $from = "admin@designsbytabitha.ca";
          $headers = "From:" . $from;
          mail($to,$subject,$message,$headers);
          
          header('Location: reset_password_success.php');
        } else {
          $statusMessage = $stmt->error;
        }
      }
      
      $con->close();
        
    } else {
        $statusMessage = "Please fix the errors and try again";
    }
  }
  
  function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  }
    
?>

  <div id="content">
    <h1>Designs by Tabitha</h1>
    <h2>Reset Password</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

      <input type="text" name="email" placeholder="Email:" value="<?php echo $email;?>"
        style="font-size: 150%;"><span class="error"> * </span>
      <br>
      <span class="error"><?php echo $emailErr;?></span>
      <br>

      <input id="submitBtn" class="fancyBtn" type="submit" name="submit" value="Submit">

    </form>

    <h2 id="errorBox"><?php echo $statusMessage; ?></h2>
    <br>
    <h2 id="errorBox"><?php echo $success; ?></h2>
    <br>
    <h2 id="errorBox"><?php echo $errorMessage; ?></h2>

    <a href="./session.php" style="width: 100%; text-align: center;">
        <button class="fancyBtn">
            Back
        </button>
    </a>

  </div>

  <script>
  </script>

</body>
</html>