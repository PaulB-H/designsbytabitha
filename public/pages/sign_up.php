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

    .disabled {
      background-color: darkgray;
    }
  </style>
</head>

<body>

  <?php

    // These values are echoed back into our form!
    $userName = $password = $email = "";

    // We set these once we know the posted value is valid
    $validUserName = $validPassword = $validEmail = "";

    // If any of these are set, form submission fails
    // We echo these values back into the form under their respective fields
    $userNameErr = $passwordErr = $emailErr = "";

    // Used when email unavailable, or other backend error.
    // Also if username, password, or email are invalid,
    // it will be set to "Please fix errors and try again"
    $result = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $userName = $_POST["userName"];
      $password = $_POST["password"];
      $email = $_POST["email"];

      if (empty($_POST["userName"])) {
        $userNameErr = "User Name is required<br>";
      } else {
        $userName = $_POST["userName"];
        if (!preg_match("/^[a-zA-Z]{4,10}$/", $userName)) {
          $userNameErr = "Only letters, 4 to 10 characters<br>";
        } else {
          $validUserName = $userName;
        }
      }
      
      if (empty($_POST["password"])) {
        $passwordErr = "Password is required<br>";
      } else {
        $password = sanitize($_POST["password"]);
        if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z]{6,})(?=.*[a-zA-Z])[0-9a-zA-Z]{7,25}$/", $password)) {
          $passwordErr = "At least 6 letters and 1 number<br>";
        } else { 
          $validPassword = $password;
        } 
      }
      
      if (empty($_POST["email"])) {
        $emailErr = "Email is required<br>";
      } else {
        $email = sanitize($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid email format";
        } else { 
          $validEmail = $email;
        }
      }
      
      // If any errors are set do not proceed
      if (!empty($userNameErr) || !empty($passwordErr) || !empty($emailErr)) {
        $result = "Please fix the errors and try again";
      } else {
        include "../../pdo_config_get.php";
        $pdo = getConnection();

        // Check for duplicate email before inserting
        $checkStmt = $pdo->prepare("SELECT Email FROM users WHERE Email = ?");
        $checkStmt->execute([$validEmail]);
        $duplicateEmail = $checkStmt->fetchColumn();

        if ($duplicateEmail) {
          $result = "Email unavailable.";
        } else {
          // No duplicate email, proceed with insertion
          $hashedPassword = password_hash($validPassword, PASSWORD_BCRYPT);

          // todo: Change default roles to be "user" in the table
          $roles = "user";

          // We added a UUID as default in MariaDB
          // This is only uuidv1, but works for this
          // UserID VARCHAR(36) DEFAULT (UUID());
          $query = "INSERT INTO users (UserName, Password, Email, Roles) VALUES (:username, :password, :email, :roles)";
          $stmt = $pdo->prepare($query);
          $stmt->bindParam(':username', $validUserName, PDO::PARAM_STR);
          $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
          $stmt->bindParam(':email', $validEmail, PDO::PARAM_STR);
          $stmt->bindParam(':roles', $roles, PDO::PARAM_STR);

          if ($stmt->execute()) { 
            $result = "User Registered Successfully";

            $_SESSION["user"] = $validUserName;
            $_SESSION["email"] = $validEmail;
            $_SESSION["roles"] = $roles;

            header('Location: ./sign_up_success.php');
          } else {
            // $errorInfo = $stmt->errorInfo();
            $result = "DB Error";
          }
        }
      }
    }

    function sanitize($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    // $userName = $password = $email = "";
    $validUserName = $validPassword = $validEmail = "";

  ?>

  <div id="content">
    <h1>Designs by Tabitha</h1>
    <h2 style="margin-bottom: 0; font-family: sans-serif;">Sign Up Form</h2>
    <p style="margin-bottom: 10px"><span class="error">* required field</span></p>

    <form id="sign-up-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div>
        <input id="user-name" type="text" name="userName" maxlength="10" placeholder="User Name:" value="<?php echo $userName;?>">
        <span class="error"> *</span>
      </div>

      <span id="user-name-error" class="error">
        <?php echo $userNameErr; ?>
      </span>

      <div>
        <input autocomplete="off" id="password" type="password" name="password" placeholder="Password:"
          value="<?php echo $password;?>" style="margin-top: 10px">
        <span class="error"> *</span>
      </div>
      <span id="password-error" class="error">
        <?php echo $passwordErr; ?>
      </span>
      <div>
        <input autocomplete="off" id="verify-password" type="password" name="verify-password" placeholder="verify-Password:"
          value="" style="margin-top: 10px">
        <span class="error"> *</span>
      </div>
      <span id="verify-password-error" class="error">
        <?php echo $verifyPasswordErr; ?>
      </span>

      <button id="togglePassBtn" class="fancyBtn" type="button" onclick="togglePassVisible(); toggleText();">
        Show Password
      </button>

      <div>
        <input id="email" type="text" name="email" placeholder="Email:" value="<?php echo $email; ?>">
        <span class="error"> *</span>
      </div>
      <span class="error"><?php echo $emailErr; ?></span>

      <input id="submit" class="fancyBtn" type="submit" name="submit" value="Submit">

    </form>

    <h2 id="errorBox"><?php echo $result; ?></h2>

    <a href="./session.php" style="width: 100%;">
      <button class="fancyBtn" style=" font-size: 125%; max-width: 229px;">
        ..Back
      </button>
    </a>

  </div>

  <script>

    const submitBtn = document.getElementById("submit");
    submitBtn.disabled = true;
    submitBtn.classList.add("disabled");
    
    const errorBox = document.getElementById("errorBox");

    const formInputs = document.querySelectorAll("#user-name, #password, #verify-password, #email");
    const userNameInput = formInputs[0];
    const passwordInput = formInputs[1];
    const verifyPasswordInput = formInputs[2];
    const emailInput = formInputs[3];

    let userNameValid = false;
    let passwordValid = false;
    let passwordsMatch = false;
    let emailValid = false;

    // The actual function and timeout that are used
    // to check if the form is ready to submit
    let checkAllowSubmitTimeout; 
    const checkSubmitRequirements = () => {
      if (userNameValid && passwordValid && passwordsMatch && emailValid) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("disabled")
      } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("disabled")
      }
    }
    // Attach a listener to every input so we can check
    // if the form is ready to submit and enable submit btn
    formInputs.forEach((input) => {
      input.addEventListener("input", () => {
        window.clearTimeout(checkAllowSubmitTimeout);
        checkAllowSubmitTimeout = window.setTimeout(() => {
          checkSubmitRequirements();
        }, 500)
      })
    })

    userNameInput.addEventListener("input", (e) => {
      const userNameError = document.getElementById("user-name-error")

      const userNameRegex = /^[a-zA-Z]{4,10}$/;
      userNameValid = userNameRegex.test(e.target.value);
  
      if (userNameValid) {
        userNameError.innerText = "";
      } else {
        userNameError.innerText = "Min 4 Max 10 Letters Only"
      }
    })

    let checkEmailTimeout;
    emailInput.addEventListener("input", () => {
      window.clearTimeout(checkEmailTimeout)
      checkEmailTimeout = window.setTimeout(() => {
        checkEmailValid();
      }, 250)
    })
    const checkEmailValid = () => {
      const emailRegex = /^[a-zA-Z0-9._%+-]{1,30}@[a-zA-Z0-9.-]{1,30}\.[a-zA-Z]{2,}$/i;
      emailValid = emailRegex.test(emailInput.value);
    }
    checkEmailValid();

    // On input of the verify-password field
    // Check if it and password match
    passwordInput.addEventListener("input", () => {
      window.clearTimeout(comparePassTimeout);
      comparePasswords();
    })
    verifyPasswordInput.addEventListener("input", () => {
      window.clearTimeout(comparePassTimeout);
      comparePasswords();
    })
    let comparePassTimeout;
    comparePasswords = () => {
      const verifyPasswordError = document.getElementById("verify-password-error")
      comparePassTimeout = window.setTimeout(() => {
        checkValidPassword();
        if (verifyPasswordInput.value === "") return;
        if (passwordInput.value !== verifyPasswordInput.value) {
          verifyPasswordError.innerText = "Passwords do not match"
          passwordsMatch = false;
        } else {
          passwordsMatch = true;
          verifyPasswordError.innerText = ""
        }
      }, 250);
    }

    const passwordRegex = /^(?=.*\d)(?=.*[a-zA-Z]{6,})(?=.*[a-zA-Z])[0-9a-zA-Z]{7,25}$/;
    const checkValidPassword = () => {
      passwordValid = passwordRegex.test(passwordInput.value)
      if (passwordValid) {
        document.getElementById("password-error").innerText = "";
      } else {
        document.getElementById("password-error").innerText = "At least 6 letters and 1 number";
      }
    }

    function togglePassVisible() {
      const password = document.getElementById("password");
      const verifyPassword = document.getElementById("verify-password")
      if (password.type === "password") {
        password.type = "text";
        verifyPassword.type = "text";
      } else {
        password.type = "password";
        verifyPassword.type = "password";
      }
    }

    function toggleText() {
      const togglePassBtn = document.getElementById("togglePassBtn");
      if (togglePassBtn.innerText == "Show Password") {
        togglePassBtn.innerText = "Hide Password"
      } else {
        togglePassBtn.innerText = "Show Password"
      }
    }

  </script>

</body>

</html>