<?php
session_start();

// Définit une durée de validité du jeton CSRF en secondes (par exemple, 30 minutes)
$csrf_token_lifetime = 5*60; 

// Vérifie si un jeton CSRF est déjà présent dans la session et s'il est encore valide
if (!isset($_SESSION['csrf_token']) || (isset($_SESSION['csrf_token_time']) )) {
    // Génère un nouveau jeton CSRF et met à jour son horodatage
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que le jeton CSRF dans la demande correspond à celui dans la session
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        echo "tout va bien" ;
    } else {
        // Rejet de la demande en cas de jeton CSRF invalide
        echo "Tentative de CSRF détectée!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Changer le mot de passe (avec CSRF protection et expiration)</title>
</head>
<body>
    <form action="http://localhost:8000/index.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="newpassword">Nouveau mot de passe :</label>
        <input type="password" id="newpassword" name="newpassword">
        <input type="submit" value="Changer le mot de passe">
    </form>
</body>
</html>
