<?php
$url = 'http://localhost:8000';

$dataUser = null;
$error = null;

if (sizeof($_POST) > 0) {
    $user = "root";
    $pass = "antoine";

    $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=db_blog', $user, $pass, $options);

    } catch (PDOException $e) {
        echo "error MySQL connect";
        exit;
    }

    $username = htmlentities($_POST['username'], ENT_QUOTES, 'utf-8');

    if (
        !empty($_POST['username']) && is_string($_POST['username']) &&
        !empty($_POST['password']) && is_string($_POST['password'])
    ) {
        $sth = $dbh->prepare('SELECT username FROM users WHERE username = ? AND password = ? ');
        $sth->execute([$_POST['username'], $_POST['password']]);
        $dataUser = $sth->fetch();

       if($dataUser === false)  $error = "Cet ustilisateur n'existe pas "; 
    } else {
        $error = "certain(s) pose(nt) des pb ";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Injection</title>
</head>
<?php if (!$dataUser): ?>
    <?php if ($error): ?>
        <p class="error">
            <?php echo htmlentities($error, ENT_QUOTES, 'utf-8'); ?>
        </p>
    <?php endif; ?>
    <form action="<?php echo htmlentities($url, ENT_QUOTES, 'utf-8'); ?>" method="post">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $username ?? "" ?>" />
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" name="password" />
        </p>
        <!-- todo csrf -->
        <input type="hidden" name="csrf" />
        <p>
            <button type="submit">Ok</button>
        </p>
    </form>
<?php else: ?>
    <ul>
        <?php foreach ($dataUser as $u) : ?>
            <li><?php echo htmlentities($u, ENT_QUOTES, 'utf-8');  ?></li>
        <?php endforeach ; ?>
    </ul>
<?php endif; ?>
</head>

<body>

</body>

</html>