# Utiliser l'image officielle de PHP
FROM php:8.2-cli

# Installer les dépendances nécessaires pour PDO et PDO MySQL
RUN apt-get update && docker-php-ext-install pdo pdo_mysql

# Définir le répertoire de travail à /srv
WORKDIR /srv

# Exposer le port 8000
EXPOSE 8000

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "public/index.php"]