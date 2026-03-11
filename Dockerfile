# Utilise une image Docker stable avec PHP-FPM et Nginx
FROM richarvey/nginx-php-fpm:3.1.6

# Copie tous les fichiers du projet dans le conteneur
COPY . .

# Configuration de l'image pour Laravel
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1



# Configuration Laravel spécifique à l'environnement (sera surchargée par Render)
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

RUN touch .env
# Permet à Composer de s'exécuter en tant que super-utilisateur
ENV COMPOSER_ALLOW_SUPERUSER 1

# Commande de démarrage du conteneur
CMD ["/start.sh"]