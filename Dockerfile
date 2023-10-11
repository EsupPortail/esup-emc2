###########################################################################################
#
#                               Image pour le dev.
#
###########################################################################################

ARG PHP_VERSION

FROM unicaen-dev-php${PHP_VERSION}-apache

LABEL maintainer="Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>"

RUN echo $PHP_VERSION
RUN php --version

## Installation de packages requis.
#RUN apt-get update -qq && \
#    apt-get install -y \
#        ghostscript-x \
#        php${PHP_VERSION}-imagick

# Nettoyage
RUN apt-get autoremove -y && apt-get clean && rm -rf /tmp/* /var/tmp/*

# Symlink apache access and error logs to stdout/stderr so Docker logs shows them
RUN ln -sf /dev/stdout /var/log/apache2/access.log
RUN ln -sf /dev/stdout /var/log/apache2/other_vhosts_access.log
RUN ln -sf /dev/stderr /var/log/apache2/error.log

# Configuration Apache, PHP et FPM
ADD docker/apache-ports.conf     ${APACHE_CONF_DIR}/ports.conf
ADD docker/apache-site.conf      ${APACHE_CONF_DIR}/sites-available/app.conf
ADD docker/apache-site-ssl.conf  ${APACHE_CONF_DIR}/sites-available/app-ssl.conf
ADD docker/fpm/pool.d/www.conf   ${PHP_CONF_DIR}/fpm/pool.d/
ADD docker/fpm/conf.d/99-app.ini ${PHP_CONF_DIR}/fpm/conf.d/

# Copie des scripts complémentaires à lancer au démarrage du container
COPY docker/entrypoint.d/* /entrypoint.d/

## Package PHP Oracle OCI8
ADD docker/resources/instantclient-basiclite-linux.x64-18.5.0.0.0dbru.zip /tmp/
ADD docker/resources/instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip /tmp/
ADD docker/resources/instantclient-sqlplus-linux.x64-18.5.0.0.0dbru.zip /tmp/
RUN unzip -o /tmp/instantclient-basiclite-linux.x64-18.5.0.0.0dbru.zip -d /usr/local/ && \
    unzip -o /tmp/instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip -d /usr/local/ && \
    unzip -o /tmp/instantclient-sqlplus-linux.x64-18.5.0.0.0dbru.zip -d /usr/local/ && \
    ln -s /usr/local/instantclient_18_5 /usr/local/instantclient && \
    ln -s /usr/local/instantclient/sqlplus /usr/bin/sqlplus
RUN echo 'instantclient,/usr/local/instantclient' | pecl install oci8-3.0.1
RUN echo "extension=oci8.so" > ${PHP_CONF_DIR}/fpm/conf.d/30-php-oci8.ini
RUN echo "extension=oci8.so" > ${PHP_CONF_DIR}/cli/conf.d/30-php-oci8.ini
RUN echo "/usr/local/instantclient" > /etc/ld.so.conf.d/oracle-instantclient.conf
RUN ldconfig

RUN a2ensite app app-ssl && \
    service php${PHP_VERSION}-fpm reload
