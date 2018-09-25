FROM php:7.1-fpm
MAINTAINER berseck3@gmail.com

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    # Install Cron to make sure we can run our services
    && apt-get install -y cron && apt-get -y install vim \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql

# Add crontab file in the cron directory
ADD scheduler.sh /var/www/html/scheduler.sh

# Give execution rights on the cron job
RUN chmod 0644 /var/www/html/scheduler.sh

#echo new cron into cron file
RUN (crontab -l ; echo "* * * * * sh /var/www/html/scheduler.sh >> /var/www/html/storage/logs/laravel.log 2>&1") | crontab -
RUN (crontab -l ; echo "* * * * * echo \"Hello world\" >> /var/www/html/storage/logs/laravel.log 2>&1") | crontab -
RUN (crontab -l ; echo "#just a new line to make sure cron will work") | crontab -

RUN /etc/init.d/cron stop && /etc/init.d/cron start
RUN /etc/init.d/cron stop && /etc/init.d/cron start
