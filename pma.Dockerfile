FROM phpmyadmin/phpmyadmin:latest

COPY pma-config/apache.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername
