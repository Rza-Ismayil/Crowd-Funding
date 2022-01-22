<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <link rel="stylesheet" type="text/css" href="Views/indexStyle.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Views/Error.css" media="screen">
</head>
<body>
    <div class="Heading"><b>Crowd <b class="box">Funding</b> </b></div>
    <form method="post" action="login.php" class="loginForm"><br>
        <input name="email" type="email" placeholder="Email"><br><br>
        <input name="password" type="password" placeholder="Password"><br><br>
        <input name="submit" type="submit" value="Log in" class="Button"><br>
    </form>
    <?php
        session_start();
        if(isset($_SESSION["ERROR"]))
        {
            echo $_SESSION["ERROR"];
            session_destroy();
        }
    ?>
</body>
</html>