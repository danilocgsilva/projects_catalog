FROM debian:bookworm-slim

ENV DNS=0.0.0.0

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install curl git zip vim -y
RUN apt-get install php php-mysql php-xdebug php-curl php-zip php-xml php-mbstring -y
RUN apt-get install mariadb-client -y
RUN apt-get install dos2unix -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - 
RUN apt-get install -y nodejs

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

COPY /config/xdebug.ini /etc/php/8.2/mods-available/
COPY /config/startup.sh /startup.sh
COPY /config/apache2.conf /etc/apache2/
COPY /config/000-default.conf /etc/apache2/sites-available/
RUN dos2unix /startup.sh
RUN chmod +x /startup.sh

# CMD while : ; do sleep 1000; done
CMD sh /startup.sh
