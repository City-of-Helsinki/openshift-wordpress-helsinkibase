FROM registry.access.redhat.com/ubi9/php-82:latest

ARG WP_CLI_URL="https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"
ARG COMPOSER_AUTH="{}"
ARG COMPOSER_REPOSITORIES=""
ARG COMPOSER_PACKAGES=""
ARG MOUNT_SECRET="false"

ENV PATH='/opt/app-root/src/bin:/opt/app-root/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/opt/app-root/src/vendor/bin'
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV DISPLAY_ERRORS=OFF

# ENV DOCUMENTROOT=/public

USER 0

# github openshift build volume auth
RUN mkdir -p /opt/app-root/src/.config/composer && \
    if [ -n "$MOUNT_SECRET" ] && [ "${MOUNT_SECRET,,}" = "true" ]; then \
        cp /mnt/secrets/* /opt/app-root/src/.config/composer; \
    fi

RUN dnf update -y  && \
    dnf install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm && \
    dnf install -y https://dev.mysql.com/get/mysql80-community-release-el9-5.noarch.rpm && \
    dnf install -y mysql-community-client && \
    dnf install -y msmtp && \
    dnf install -y jq && \
    dnf clean all

# Additional php-fpm settings
RUN echo "clear_env = no" >> /etc/php-fpm.d/www.conf && \
    echo "pm.max_spare_servers = 10" >> /etc/php-fpm.d/www.conf && \
    echo "catch_workers_output = yes" >> /etc/php-fpm.d/www.conf

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
    touch /var/log/msmtp.log && chmod 777 /var/log/msmtp.log && \
    chmod -R 777 /opt/app-root/src/.cache

# Set the default command for the resulting image
CMD /opt/app-root/src/.s2i/bin/run-wrapped
