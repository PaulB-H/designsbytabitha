<?php

  include("../php/session_start.php");
    
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Check User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/account.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100vh;
        }

        body {
            display: flex;
            height: 100%;
            justify-content: center;
        }

        input {
            margin: 10px;
        }

        #content {
            background: white;
            display: flex;
            /*flex-grow: 1;*/
            justify-content: center;
            /*align-items: center;*/
            text-align: center;
            flex-direction: column;
            width: 100%;
            padding: 10px;
        }

        #sign-in-form {
            display: flex;
            flex-direction: column;
        }

        .error {
            color: #FF0000;
            font-weight: bolder;
            background-color: yellow;
        }

        .highlight {
            background-color: yellow;
        }

        .button {
            font-size: 125%;
            border: none;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            max-width: 256px;
            margin: 10px auto;
        }

        input {
            font-size: 150%;
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
          $userNameErr = "User Name is required";
        } else {
          $userName = test_input($_POST["userName"]);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z]*$/",$userName)) {
            $userNameErr = "One word, only letters";
          } else {
            $validUserName = $userName;
            $myVar ++;
          }
        }
    
        if (empty($_POST["password"])) {
            $passwordErr = "Please enter your password";
        } else {
          $password = test_input($_POST["password"]);
          // regex check
          if (!preg_match("/^[a-zA-Z0-9]{6,}$/",$password)) {
          //if (!preg_match("/[a-zA-Z]{5,}[0-9]{1,}[\W\S]*/",$password)) {
            $passwordErr = "Min 6 characters";
          } else { 
            $validPassword = $password;
            $myVar ++;
          }
        }
        
        
        if ($myVar === 2){
          $myVar ++;
          
          $success ="Success";

          include "../php/config.php";
          
          $stmt = $con -> prepare('SELECT UserName, Password, TempPass, Email FROM users WHERE UserName = ?');
          $stmt -> bind_param('s', $validUserName); 
          $stmt -> execute(); 
          $stmt -> store_result(); 
          $stmt -> bind_result($name, $pass, $tempPass, $email); 
          $stmt -> fetch();
          
          
          if (password_verify($validPassword, $pass) || password_verify($validPassword, $tempPass)) {
            $success = 'Password is valid!';
            $_SESSION["user"] = $name;
            $_SESSION["email"] = $email;
            $success = "Successfully logged in as " . $_SESSION["user"];
            header('Location: ./sign_in_success.php');
          } else {
            $success = 'Invalid password.';
          }
          
          // close statement 
          $stmt->close();
          
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
        <h2 style="margin-bottom: 0; font-family: sans-serif;">Sign-In</h2>
        <p><span class="error">* required field</span></p>

        <form id="sign-in-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <span>
                <input type="text" name="userName" placeholder="User Name" value="<?php echo $userName;?>">
                <span class="error"> * </span>
            </span>

            <span class="error"><?php echo $userNameErr;?></span>

            <span>
                <input autocomplete="off" id="password" type="password" name="password" placeholder="Password"
                    value="<?php echo $password;?>">
                <span class="error"> * </span>
            </span>

            <span class="error"><?php echo $passwordErr;?></span>


            <input class="button fancyBtn" id="submit" type="submit" name="submit" value="Submit">

        </form>

        <h3 class="highlight"><?php echo $success; ?></h3>

        <a href="./session.php">
            <button class="button fancyBtn" style="font-size: 125%;">
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


    </script>

</body>

</html>