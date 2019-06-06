# ubitransport
Test technique Ubitransport

## Déploiement
Déployer sur un serveur web, vous pouvez directement cloner le repository ans le répertoire de destination. Le serveur web doit être configuré pour servir le fichier public/index.php

## Téléchargement des dépendances
composer install

yarn install

## Fichier .env.local.php
``` php
<?php
return array (
  'APP_ENV' => 'prod',
  'APP_SECRET' => 'jeveuxrencontrerubitransport',
  'DATABASE_URL' => 'DATABASE_URL=mysql://ubitransport:lionelSengkouvanh@10.3.0.120:3306/ubitransport',
  'MAILER_URL' => 'null://localhost',
  'APP_DEBUG' => 'false',
);
```

Ajouter le fichier .env.local.php à la racine de l'application en remplaçant par la configuration de production. 

## Préparation du cache
php bin/console cache:clear --env production --no-debug

## Initialisation de la base de données
php bin/console doctrine:migrations:migrate

Normalement, vous êtes prêt à tester!