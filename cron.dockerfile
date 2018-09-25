FROM ubuntu:latest
MAINTAINER berseck3@gmail.com

# Add crontab file in the cron directory
ADD crontab /etc/cron.d/schedule-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/schedule-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

#Install Cron
RUN apt-get update
RUN apt-get -y install cron
RUN apt-get -y install vim
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php php-fpm php-mysql

CMD cron && tail -f /var/log/cron.log
