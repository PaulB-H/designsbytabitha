<?php
  include("../php/session_start.php");
  include("../../smtp_config.php");
  // Access SMTP configuration
  $smtpHost = $smtpConfig['host'];
  $smtpUsername = $smtpConfig['username'];
  $smtpPassword = $smtpConfig['password'];
  $smtpPort = $smtpConfig['port'];

  $email = "";
  $emailErr = "";
  $statusMessage = "";
  $success = "";
  $errorMessage = "";

  //Import PHPMailer classes into the global namespace
  //These must be at the top of your script, not inside a function
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require '../../PHPMailer/Exception.php';
  require '../../PHPMailer/PHPMailer.php';
  require '../../PHPMailer/SMTP.php';

  $mail = new PHPMailer(true);
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
  $statusMessage = "Down for now...";
  exit();

  $myVar = 0;

  if (empty($_POST["email"])) {
      $emailErr = "<br>Email is required<br>";
  } else {
      $email = test_input($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $validEmail = $email;
        $myVar++;
    }
  }

  if ($myVar === 1) {
      include "../../config.php";

      $stmt = $con->prepare("SELECT UserName FROM users WHERE Email = ?");
      $stmt->bind_param("s", $validEmail);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($match);
      $stmt->fetch();

      if ($match == "") {
          $statusMessage = "No user found";
      } else {
          $validUser = true;
      }

      if ($validUser) {
          // Generate password
          $tempPass = generateRandomPassword();
          $hashedTempPass = password_hash($tempPass, PASSWORD_BCRYPT);

          $stmt = $con->prepare("UPDATE users SET TempPass = ? WHERE Email = ?");
          $stmt->bind_param("ss", $hashedTempPass, $validEmail);
          $stmt->execute();

          if ($stmt->affected_rows > 0) {
              // Update successful
              $statusMessage = "Password updated successfully<br />";

              // Check the number of emails sent in the last 24 hours
              $sql = "SELECT COUNT(*) as count FROM email_log WHERE sent_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
              $result = $con->query($sql);

              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $emailCount = $row['count'];

                  // Check if the limit is exceeded
                  if ($emailCount < 3) {
                      // Send the email
                      // ... (your email sending code here)
                      // Send email
                      $mail->isSMTP();
                      $mail->SMTPDebug = SMTP::DEBUG_OFF;
                      $mail->Host = $smtpHost;
                      $mail->SMTPAuth = true;
                      $mail->Username = $smtpUsername;
                      $mail->Password = $smtpPassword;
                      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                      $mail->Port = $smtpPort;

                      $mail->setFrom($smtpUsername, 'devbymail mailer');
                      $mail->addAddress($smtpUsername, 'Paul BH');
                      $mail->isHTML(true);
                      $mail->Subject = 'Password Reset - Designs by Web';
                      $mail->Body = "Your temporary password for domain.ca is: {$tempPass}";

                      try {
                          $mail->send();
                          $statusMessage .= 'Temporary password emailed successfully';
                      } catch (Exception $e) {
                          $statusMessage .= " <br /> | Error mailing result...";
                      }
              
                      // Log the sent email
                      $sql = "INSERT INTO email_log () VALUES ()";
                      $con->query($sql);
                  } else {
                      // echo "Email limit exceeded.";
                      $statusMessage = "Email limit reached...<br />";
                  }
              } else {
                  echo "Error: " . $con->error;
              }


          } else {
              // Update failed
              $statusMessage = "Password update failed";
          }
      }

      $stmt->close();
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

function generateRandomPassword() {
  $characters = "ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz123456789";
  $length = 8;
  $tempPass = "";

  for ($i = 0; $i < $length; $i++) {
      $tempPass .= $characters[rand(0, strlen($characters) - 1)];
  }

  return $tempPass;
}
    
?>

  <div id="content">
    <h1>Designs by Tabitha</h1>
    <h2>Reset Password</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <input type="text" name="email" placeholder="Email:" value="<?php echo htmlspecialchars($email); ?>"
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