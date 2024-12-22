# Projet Symfony - Plateforme d'Exercices

Ce projet est une plateforme Symfony permettant de créer, gérer et résoudre des exercices, avec une interface d'administration et des fonctionnalités avancées.

## Prérequis

Assurez-vous d'avoir installé les outils suivants :

- **Git** : Pour cloner le projet.
- **Docker** et **Docker Compose** : Pour exécuter l'environnement de développement.
- **Composer** : Pour gérer les dépendances PHP (si Docker n'est pas utilisé).

## Installation

### 1. Cloner le projet

Clonez le dépôt depuis votre espace GitHub ou GitLab :

```bash
git clone https://github.com/DvK42/symfony_sujet_exercice.git
cd symfony_sujet_exercice
```

---

### 2. Configuration

#### a. Créer le fichier `.env.local`

Dupliquez le fichier `.env` en `.env.local` pour configurer vos variables d'environnement locales :

```bash
cp .env .env.local
```

Modifiez les variables si nécessaire, notamment pour le **DSN Mailer** et la **base de données** :

```
DATABASE_URL="mysql://root:root@db:3306/esgisymfoexercices"
MAILER_DSN="smtp://mailhog:1025"
```

---

### 3. Lancer l'environnement Docker

Utilisez Docker pour lancer les conteneurs nécessaires :

```bash
docker-compose up -d --build
```

Cela démarre les services suivants :

- **PHP** : Serveur Symfony.
- **MariaDB** : Base de données.
- **MailHog** : Outil pour capturer les emails en développement.

---

### 4. Installer les dépendances

Si vous utilisez Docker, exécutez les commandes suivantes à l'intérieur du conteneur PHP :

```bash
docker-compose exec php composer install
```

Si Docker n'est pas utilisé :

```bash
composer install
```

---

### 5. Créer la base de données et appliquer les migrations

Exécutez les commandes suivantes pour configurer la base de données :

```bash
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate
```

---

### 6. Charger les données de test (Fixtures)

Si vous souhaitez précharger des données dans la base de données, exécutez :

```bash
docker-compose exec php bin/console doctrine:fixtures:load
```

---

### 7. Accéder à l'application

L'application est maintenant accessible à l'adresse suivante :

```
http://localhost:8000
```

L'interface d'administration est accessible ici (connexion requise) :

```
http://localhost:8000/admin
```

---

## Outils supplémentaires

### MailHog

MailHog capture les emails envoyés par l'application en environnement de développement. Accédez à son interface via :

```
http://localhost:8025
```

### Base de données

Pour visualiser la base de données, connectez-vous au conteneur MySQL ou utilisez un outil comme **phpMyAdmin** avec les informations suivantes :

- **Hôte** : `localhost` (ou `db` si dans Docker).
- **Port** : `3306`
- **Utilisateur** : `root`
- **Mot de passe** : `root`

---

## Développement

Pour travailler sur le projet, assurez-vous que les conteneurs Docker sont en cours d'exécution. Vous pouvez également utiliser le serveur Symfony local (si Docker n'est pas utilisé) avec la commande suivante :

```bash
symfony serve
```
