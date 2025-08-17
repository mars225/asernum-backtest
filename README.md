# Hotel Reservation Management API (ASERNUM-BACKTEST)

## Présentation

Cette API RESTful développée avec Laravel 12 permet la gestion complète des réservations de chambre d'hôtels. Elle offre deux interfaces distinctes :

- **Interface Administration** : Gestion des hôtels, chambres, clients et réservations
- **Interface Client** : Consultation des disponibilités et gestion des réservations personnelles

L'API implémente un système d'authentification sécurisé avec Sanctum, des validations, des logs, et respecte les principes REST avec une architecture claire et modulaire.

## Stack Technique

- **Framework** : Laravel 12
- **Authentification** : Laravel Sanctum
- **ORM** : Eloquent
- **Base de données** : PostgreSQL / MongoDB (configurable)
- **Tests** : PHPUnit
- **Documentation** : Swagger (Laravel OpenAPI)
- **Architecture** : Services, Repositories, Form Requests, Resources, Policies

## Fonctionnalités Principales

### Administration (`/api/admin`)
- 🔐 Authentification des administrateurs
- 👥 Gestion des utilisateurs (managers, editors)
- 🏨 CRUD complet des hôtels
- 🛏️ CRUD complet des chambres
- 👤 Consultation des clients
- 📋 Gestion des réservations (validation, démarrage, clôture, annulation)

### Interface Communes (`/api`)
- 📝 Inscription et connexion des clients
- 🔍 Consultation des chambres disponibles
- 📅 Création de réservations avec vérification de disponibilité
- 📋 Gestion des réservations personnelles
- 🔍 Recherche de chambres disponibles par période

## Installation

### Prérequis

- PHP 8.2+
- Composer
- PostgreSQL
- Extension pdo pgsql
- Extension pdo sqlite

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd asernum-backtest
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer la base de données**
   
   Éditer le fichier `.env` :
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=hotel_reservations
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Exécuter les migrations et seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```
   
   Cette commande va :
   - Créer la structure de base de données
   - Générer des comptes administrateurs de test
   - Créer des données de démonstration (Hotels et Chambres)

6. **Générer la documentation Swagger**
   ```bash
   php artisan l5-swagger:generate
   ```

7. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

L'API sera accessible à l'adresse : `http://localhost:8000`

### Configuration additionnelle

## Utilisation

### Authentification

#### Administrateurs
```bash
POST /api/login
{
    "email": "admin1@gmail.com",
    "password": "admin1"
}
```

#### Clients
```bash
POST /api/register
{
    "pseudo": "johndoe",
    "email": "john@example.com",
    "password": "password",
    "nom": "John Doe",
    "mobile": "+1234567890"
}
```

### Documentation API

- **Swagger UI** : `http://localhost:8000/api/documentation`
- **Collection Postman** : Voir le fichier `/storage/api-docs/api-docs.json`

## Tests
```bash
# Recharger les config et vérifier que les tests s'exécuteront en mémoire sqlite :memory
php artisan config:clear

php artisan config:show database --env=testing

```
### Lancer les tests
```bash
# Tous les tests
php artisan test

# Tests unitaires seulement
php artisan test --testsuite=Unit

# Tests fonctionnels seulement
php artisan test --testsuite=Feature

# Avec couverture (si vous avez Xdebug)
php artisan test --coverage
```
- **Rapport de couverture des tests** : `http://localhost:8000/coverage`

### Structure des tests
- `tests/Unit/` : Tests unitaires (modèles, services)
- `tests/Feature/` : Tests fonctionnels (endpoints API)
- `tests/TestCase.php` : Classes de base pour les tests

## Architecture

```
app/
├── Http/
│   ├── Controllers/     # Contrôleurs API
│   ├── Requests/        # Form Requests (validation)
│   ├── Resources/       # API Resources (transformation)
│   └── Middleware/      # Middleware personnalisés
├── Models/              # Modèles Eloquent
├── Services/            # Logique métier
├── Repositories/        # Couche d'accès aux données
├── Policies/            # Policies d'autorisation
└── Traits/              # Traits réutilisables
```

## Sécurité

- Authentification Sanctum avec tokens
- Middleware de vérification des rôles
- Validation des données d'entrée
- Gestion des erreurs sécurisée

## Endpoints Principaux

### Authentification
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| POST | `/api/register` | Inscription client | - |
| POST | `/api/login` | Connexion | - |
| POST | `/api/logout` | Déconnexion | Bearer |
| GET | `/api/me` | Profil utilisateur | Bearer |

### Gestion des Hôtels
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/hotels` | Liste des hôtels | Bearer |
| GET | `/api/hotels/{id}` | Détails d'un hôtel | Bearer |
| POST | `/api/admin/hotels` | Créer un hôtel | Admin |
| POST | `/api/admin/hotels/{id}` | Mettre à jour un hôtel | Admin |
| DELETE | `/api/admin/hotels/{id}` | Supprimer un hôtel | Admin |

### Gestion des Chambres
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/hotels/{hotelId}/rooms` | Chambres d'un hôtel | Bearer |
| GET | `/api/rooms/{id}` | Détails d'une chambre | Bearer |
| POST | `/api/admin/hotels/{hotelId}/rooms` | Créer une chambre | Admin |
| PUT | `/api/admin/rooms/{id}` | Mettre à jour une chambre | Admin |
| DELETE | `/api/admin/rooms/{id}` | Supprimer une chambre | Admin |

### Gestion des Utilisateurs (Admin)
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/admin/users` | Liste des utilisateurs | Admin |
| GET | `/api/admin/users/{id}` | Détails d'un utilisateur | Admin |
| POST | `/api/admin/users` | Créer un utilisateur | Admin |

### Gestion des Réservations
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/reservations` | Liste des réservations | Bearer |
| GET | `/api/reservations/{id}` | Détails d'une réservation | Bearer |
| POST | `/api/reservations` | Créer une réservation | Bearer |
| PUT | `/api/reservations/{id}` | Modifier une réservation | Bearer |
| DELETE | `/api/reservations/{id}` | Annuler une réservation | Bearer |
| PUT | `/api/admin/reservations/{id}/start` | Démarrer une réservation | Admin |
| PUT | `/api/admin/reservations/{id}/close` | Clôturer une réservation | Admin |

### Disponibilité
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/hotels/{hotelId}/available-rooms` | Chambres disponibles | Bearer |

## Filtrage et Pagination

Tous les endpoints de liste supportent les paramètres suivants :
- `per_page` : Nombre d'éléments par page
- Filtres spécifiques selon l'endpoint :
  - Hôtels : `label`, `city`
  - Chambres : `type`
  - Utilisateurs : `name`, `email`, `role`

## Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commit les changements (`git commit -am 'Ajout nouvelle fonctionnalité'`)
4. Push vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créer une Pull Request

## Support

Pour toute question ou problème :
- Consulter la documentation Swagger
- Vérifier les logs Laravel (`storage/logs/`)
- Exécuter les tests pour identifier les régressions

## Licence

Ce projet est sous licence MIT.
