# Api pour mon projet pour le BusinessCase (DWWM SJSR 5)

## Projet Symfony


 Lancer les commandes suivantes une fois le projet téléchargé:
----------
* Installer les dépendances :
  * ```
    symfony composer install
    ```
  * ```
    yarn install
    ```
* Une fois le chemin de la BDD défini dans le .env (ou .env.local)
  * ```
    symfony console database:create
    ```
  * ```
    symfony console d:m:m
    ```
  * ```
    symfony console hautelook:fixtures:load --purge-with-truncate
    ```
    

***
***


> Bien penser à effectuer un ```yarn watch``` avant de lancer le serveur avec ```symfony serve```


***

> Un utilisateur test a été créé dans le but de tester la connexion avec le token JWT sur l'API
> * email : ```test@test.fr```
> * password : ```test123```

***

La commande ```symfony app:city``` a été créée afin d'importer également en BDD les communes de France depuis l'API du gouvernement dans le but d'avoir des données précises pour la mise en production éventuelle.
