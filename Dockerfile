FROM registry.access.redhat.com/ubi9/php-81:1-25

ARG WP_CLI_URL="https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"

ENV PATH='/opt/app-root/src/bin:/opt/app-root/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/opt/app-root/src/vendor/bin'
ENV AZURE_SQL_SSL_CA_PATH='/usr/local/share/ca-certificates/DigiCertGlobalRootCA.crt.pem'
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV DISPLAY_ERRORS=OFF
# ENV DOCUMENTROOT=/public

USER 0

RUN dnf update -y  && \
    dnf install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm && \
    dnf install -y https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm && \
    dnf install -y mysql-community-client && \
    dnf install -y msmtp && \
    dnf clean all

# Additional php-fpm settings
RUN echo "clear_env = no" >> /etc/php-fpm.d/www.conf

# CA cert for MySQL Database
RUN mkdir -p /usr/local/share/ca-certificates && \
    wget https://dl.cacerts.digicert.com/DigiCertGlobalRootCA.crt.pem -O $AZURE_SQL_SSL_CA_PATH

# WP CLI
RUN wget $WP_CLI_URL -O /usr/bin/wp && \
    chmod +x /usr/bin/wp

ADD . /tmp/src/

# Install the dependencies
RUN chmod +x /tmp/src/.s2i/bin/assemble-wrapped /tmp/src/.s2i/bin/run-wrapped && /tmp/src/.s2i/bin/assemble-wrapped

# Remove part which runs file permission operations
RUN sed -i '/mkdir -p ${PHP_FPM_RUN_DIR}/,/chown -R 1001:0 ${PHP_FPM_LOG_PATH}/d' /usr/libexec/s2i/run

# Set proper permissions
RUN mkdir -p ${PHP_FPM_RUN_DIR} && \
    chmod -R a+rwx ${PHP_FPM_RUN_DIR} && \
    chown -R 1001:0 ${PHP_FPM_RUN_DIR} && \
    mkdir -p ${PHP_FPM_LOG_PATH} && \
    chmod -R a+rwx ${PHP_FPM_LOG_PATH} && \
    chown -R 1001:0 ${PHP_FPM_LOG_PATH} && \
    chmod -R a+rwx ${PHP_SYSCONF_PATH}/php.ini && \
    chmod -R a+rwx ${PHP_SYSCONF_PATH}/php.d/10-opcache.ini && \
    chmod 777 /run/httpd && \
    chmod 777 ~/.msmtprc && \
    touch /var/log/msmtp.log && chmod 777 /var/log/msmtp.log

# PHP settings
RUN sed -i 's|memory_limit = 128M|memory_limit = 256M|g' ${PHP_SYSCONF_PATH}/php.ini && \
    sed -i 's|post_max_size = 200M|post_max_size = 20M|g' ${PHP_SYSCONF_PATH}/php.ini && \
    sed -i 's|upload_max_filesize = 200M|upload_max_filesize = 20M|g' ${PHP_SYSCONF_PATH}/php.ini && \
    sed -i 's|;error_log = syslog|error_log = /opt/app-root/src/wp-content/uploads/logs/error.log|g' ${PHP_SYSCONF_PATH}/php.ini

# Set the default command for the resulting image
CMD /opt/app-root/src/.s2i/bin/run-wrapped
