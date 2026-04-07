# Gestion d’événements — Win’s Events

Plateforme **tout-en-un** pour organiser des soirées/événements, vendre/réserver des places, et gérer l’entrée sur site avec un **scanner QR**.
L’objectif est simple : une expérience fluide pour le public, et un back-office clair pour l’équipe.

## Ce que fait l’application (en 30 secondes)

- **Avant l’événement** : création de la soirée, paramétrage, publication.
- **Pendant** : contrôle d’accès rapide (caméra ou saisie), mise à jour instantanée des présents.
- **Après** : suivi des commandes/billets, exports et pièces (facture PDF).

## Fonctionnalités clés

- **Espace public** : catalogue de soirées, pages de détails, réservation, paiement/checkout, page commande, facture PDF.
- **Espace admin** : dashboard, gestion des soirées, réservations/commandes (filtres, export), billets, gestion des comptes contrôleurs.
- **Espace contrôleur (scanner)** : choix d’une soirée, scan QR (caméra + saisie manuelle), compteur de présence.

## Stack technique

- **Monolitique** : Laravel 12 (PHP)
## Démarrage rapide (local)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configurez votre base dans `.env`, puis :

```bash
php artisan migrate
php artisan db:seed
npm install
npm run dev
php artisan serve
```

## Prérequis

- **PHP** \(>= 8.2\) et extensions usuelles Laravel.
- **Composer** \(>= 2\).
- **Node.js** et **npm** (pour Vite/Tailwind).
- Une base de données **MySQL/MariaDB** (ou SQLite en local si vous adaptez la config).

Versions observées dans l’environnement de dev :
- Laravel **12.x**
- PHP **8.x**
- Vite **7.x**, Tailwind **4.x**

## Installation

1) Installer les dépendances PHP :

```bash
composer install
```

2) Créer le fichier d’environnement :

```bash
cp .env.example .env
php artisan key:generate
```

3) Configurer la base de données dans `.env` :

- `DB_CONNECTION`
- `DB_HOST`, `DB_PORT`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

4) Migrer la base :

```bash
php artisan migrate
```

5) Lancer les seeders (rôles + comptes par défaut en dev) :

```bash
php artisan db:seed
```

Les identifiants par défaut peuvent être surchargés via :
- `DEFAULT_ADMIN_EMAIL`, `DEFAULT_ADMIN_PASSWORD`
- `DEFAULT_CONTROLLER_EMAIL`, `DEFAULT_CONTROLLER_PASSWORD`

6) Installer et builder les assets front :

```bash
npm install
npm run build
```

## Démarrage en développement

Option A — démarrage “simple” :

```bash
php artisan serve
```

Dans un autre terminal (pour le front) :

```bash
npm run dev
```

Option B — commande “tout-en-un” (si disponible) :

```bash
composer run dev
```

## Tests

```bash
php artisan test
```

## Accès et routes principales

- **Public** : `/`, `/soirees`, `/soirees/{slug}`, `/contact`
- **Connexion** : `/login`
- **Admin** : `/admin/*` (middleware `auth` + rôle `admin`)
- **Scanner** : `/scanner/*` (middleware `auth` + rôles `admin` ou `controller`)

## Notes utiles

- Le build front est géré par **Vite** et les styles par **Tailwind CSS v4** via `resources/css/app.css`.
- Le scanner QR s’appuie sur une lib côté navigateur (voir `resources/views/scanner/event.blade.php`).
