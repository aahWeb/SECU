# CSRF Cross-Site Request Forgery

## Définition

Une attaque CSRF se produit lorsque l'attaquant persuade un utilisateur authentifié de visiter une page web malveillante ou de cliquer sur un lien contenant une requête HTTP, généralement une demande POST, qui exécute une action sur un site web au nom de l'utilisateur sans son consentement. Cette attaque peut entraîner des actions indésirables telles que la modification des informations de l'utilisateur, la publication de contenu non autorisé ou même le changement de mot de passe.

## Problème et solution

Lorsqu'un utilisateur connecté visite la page malveillante (peut-être en cliquant sur un lien envoyé par e-mail), leur navigateur envoie automatiquement la demande POST à http://monsite.fr/chagne.php avec le nouveau mot de passe défini par l'attaquant, sans que l'utilisateur en soit conscient. Cela peut potentiellement changer le mot de passe de l'utilisateur sans son consentement.

Pour prévenir les attaques CSRF, vous pouvez utiliser des jetons CSRF.

1. À chaque fois qu'un utilisateur se connecte, générez un jeton CSRF unique et stockez-le dans une session.

2. Incluez ce jeton CSRF dans tous les formulaires que l'utilisateur soumet.

3. Lorsque le formulaire est soumis, vérifiez que le jeton CSRF inclus dans la demande correspond à celui stocké dans la session de l'utilisateur. Si les jetons ne correspondent pas, la demande doit être rejetée.

Voici un exemple simplifié en PHP :

```php

<?php
session_start();

// Génère un jeton CSRF unique et le stocke dans la session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que le jeton CSRF dans la demande correspond à celui dans la session
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Traitement du formulaire
        // ...
    } else {
        // Rejet de la demande en cas de jeton CSRF invalide
        echo "Tentative de CSRF détectée!";
    }
}
?>

```

La partie HTML

```html

<!DOCTYPE html>
<html>
<head>
    <title>Changer le mot de passe (avec CSRF protection)</title>
</head>
<body>
    <form action="changepassword.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="newpassword">Nouveau mot de passe :</label>
        <input type="password" id="newpassword" name="newpassword">
        <input type="submit" value="Changer le mot de passe">
    </form>
</body>
</html>

```

## Exercice CSRF

Reprendre l'exemple précédent et introduire plus de sécurité en introduisant la notion de temps.