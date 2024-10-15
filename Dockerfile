FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
RUN a2enmod ssl 
RUN a2enmod socache_shmcb 
RUN a2enmod headers
COPY Config-File/localhost+1.pem /etc/ssl/certs/certificate.crt
COPY Config-File/localhost+1-key.pem /etc/ssl/private/server.key
COPY Config-File/ssl-params.conf /etc/apache2/conf-available/ssl-params.conf
COPY Config-File/novelarchive-ssl.conf /etc/apache2/sites-available/novelarchive-ssl.conf

RUN a2enconf ssl-params.conf
RUN a2ensite novelarchive-ssl.conf
RUN service apache2 restart
RUN apt-get update && apt-get upgrade -y
EXPOSE 80
EXPOSE 443