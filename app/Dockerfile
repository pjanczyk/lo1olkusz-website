FROM php:7.1-apache

# install cron and pdo_mysql
RUN apt-get update \
 && apt-get install -y cron \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install pdo pdo_mysql

# enable mod_rewrite
RUN a2enmod rewrite expires

# copy php.ini configuration
COPY php.ini /usr/local/etc/php/

# copy cron task
COPY crontab /etc/cron.d/app-task
COPY crontask.sh /usr/local/bin

# fix permissions
RUN chmod +x /usr/local/bin/crontask.sh \
 && touch /var/log/cron.log \
 && chmod 777 /var/log/cron.log

# copy app's source files
COPY code/ /var/www/html/

CMD cron && apache2-foreground
