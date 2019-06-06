# ubitransport
Test technique Ubitransport

## Déploiement
Déployer sur un serveur web, vous pouvez directement cloner le repository ans le répertoire de destination. Le serveur web doit être configuré pour servir le fichier public/index.php

## Fichier .env
Renseigner la chaîne de connexion à la base de données Mysql, par exemple :
DATABASE_URL=mysql://ubitransport:lionelSengkouvanh@10.3.0.120:3306/ubitransport

## Téléchargement des dépendances
composer install
yarn install

## Préparation du cache
php bin/console cache:clear --env production --no-debug
