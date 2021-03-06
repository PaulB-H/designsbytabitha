<?php
    include("../php/session_start.php");
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/account.css">
    <style>
        .error {
            color: #FF0000;
        }

        button {
            font-size: 125%;
            margin: 10px;
            border: none;
            width: 100%;
            padding: 3px 0;
        }

        #container {
            display: flex;
            justify-content: center;
            background-color: white;
            padding: 25px;
        }
    </style>
</head>

<body>
    <div id="container">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <h1>Welcome <b><i><?php echo $_SESSION["user"]; ?></i></b></h1>
            <button class="fancyBtn" onclick="location.href = './session.php'">
                Back
            </button>
        </div>
    </div>

</body>

</html>