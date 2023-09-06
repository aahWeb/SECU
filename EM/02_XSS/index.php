<?php
if (sizeof($_POST) > 0) {
    $comment = $_POST['comment'];
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    echo "Commentaire : " . $comment;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS</title>
</head>

<body>
    <!--
    <div
        style="color: #6b6b6b;background-color:#fefffe;position:absolute;top:0;left:0;z-index:1000;width:100%;height:100%;">
        <div style="margin:auto;width:350px;">
            <div><br>
                <p style="text-align:center;"><img src="/dvwa/images/login_logo.png"></p><br>
            </div>
            <form action="http://localhost:8000/attaque.php" method="post">
                <fieldset><label for="user">Username</label> <input type="text" class="loginInput" size="20"
                        name="username"><br><label for="pass">Password</label> <input type="password" class="loginInput"
                        autocomplete="off" size="20" name="password"></fieldset>
                <p style="text-align:center;font-size:14px" class="submit"><input type="submit" value="Login"
                        name="Login"></p>
            </form>
        </div>
    </div>
            -->
    <form method="post" action="http://localhost:8000">
        <p>
            <input type="text" name="username" />
        </p>
        <p>
            <textarea name="comment" id="" cols="30" rows="10"></textarea>
        </p>

        <p>
            <button type="submit">Ok</button>
        </p>
    </form>
</body>

</html>