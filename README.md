# Jeux de cartes - Backend

## Description
Ce projet est une application PHP utilisant le framework Symfony. Il inclut un service pour générer et trier des objets "Palm", qui sont des collections d'objets "Card". Le projet suit l'architecture hexagonale et respecte les bonnes pratiques de développement logiciel.

## Installation

### Prérequis
- Git
- Docker

### Étapes

1. **Cloner le dépôt :**
    ```sh
    git clone https://github.com/MokhTunisie/jeux-de-cartes-back.git
    cd jeux-de-cartes-back
    ```

2. **Démarrer les conteneurs Docker :**
    ```sh
    docker-compose up -d
    ```

3. **Installer les dépendances dans le conteneur :**
    ```sh
    docker-compose exec php composer install
    ```

4. **Configurer les variables d'environnement :**
   Copier le fichier `.env` et ajuster les paramètres si nécessaire.
    ```sh
    cp .env .env.local
    ```

5. **Exécuter les tests PHPUnit :**
    ```sh
    docker-compose exec php ./vendor/bin/phpunit
    ```
   Ou pour afficher le pourcentage de couverture :
    ```sh
    docker-compose exec php ./vendor/bin/phpunit --coverage-text
    ```

6. **Analyser le code avec PHPStan :**
    ```sh
    docker-compose exec php ./vendor/bin/phpstan analyse
    ```

7. **Accéder à l'application :**
   Ouvrez votre navigateur et naviguez vers `http://localhost:8080`.

## Architecture

### Vue d'ensemble
Le projet suit une approche Domain-Driven Design (DDD), ce qui aide à gérer la complexité de la logique métier. Les principales couches sont :

- **Couche Domaine :** Contient la logique métier et les modèles de domaine.
- **Couche Application :** Contient les services qui orchestrent les cas d'utilisation.
- **Couche Infrastructure :** Contient les détails d'implémentation comme les dépôts et les services externes.
- **Couche UI :** Contient les contrôleurs et les vues.

### Composants clés

- **DTOs (Data Transfer Objects) :** Utilisés pour transférer des données entre les couches.
- **Services :** Contiennent la logique métier et interagissent avec les modèles de domaine.
- **Contrôleurs :** Gèrent les requêtes et réponses HTTP.

### Bonnes pratiques

- **Principes SOLID :** Le projet adhère aux principes SOLID pour assurer la maintenabilité et la scalabilité.
- **Injection de dépendances :** Utilisée pour gérer les dépendances et promouvoir un couplage lâche.
- **Tests unitaires :** Le projet inclut des tests unitaires pour assurer le non régression.
- **Qualité du code :** Le code est formaté et suit les standards PSR (phpstan niveau 8).
- **Automatisation :** Les commits sont vérifiés par des hooks git (phpunit et phpstan).

## Utilisation

### Endpoints

- **GET /api/palm/random :** Génère un Palm aléatoire.
- **POST /api/palm/sorted :** Trier un Palm donné.

## Documentation de l'API

La documentation de l'API est générée avec NelmioApiDocBundle. Pour y accéder, ouvrez votre navigateur et naviguez vers `http://localhost:8080/api/doc`.

## Auteurs

- **Mokhtar OUNIS** - *Développeur principal*