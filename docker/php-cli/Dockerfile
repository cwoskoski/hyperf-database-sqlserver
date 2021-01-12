FROM php:7.4-zts-buster

ENV SWOOLE_VERSION=4.5.9

RUN set -xe && \
    apt-get update && \
    apt-get install -y $PHPIZE_DEPS libpq-dev netcat-traditional git openssl libssl-dev libcurl4-openssl-dev autoconf libzip-dev && \
    docker-php-ext-install sockets exif zip pdo_pgsql && \
    docker-php-ext-enable sockets exif zip pdo_pgsql

# persistent / runtime deps
ENV PHPIZE_DEPS \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    re2c

ENV ACCEPT_EULA=Y

RUN apt-get update \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
        libgpgme11-dev \
        gnupg2 \
        libcurl4 \
        wget \
        zlib1g-dev \
        libzip-dev \
        libldap-common \
        libldap2-dev \
        locales \
        apt-transport-https \
        git \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

RUN set -xe && \
    curl -LO https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz && \
    tar xzf v${SWOOLE_VERSION}.tar.gz && \
    cd swoole-src-${SWOOLE_VERSION} && \
        phpize && \
        ./configure --enable-openssl --with-openssl-dir=/usr/include/openssl && \
        make -j && \
        make && \
        make install && \
    cd ../.. && rm -rf v${SWOOLE_VERSION}.tar.gz swoole-src-${SWOOLE_VERSION} && \
    docker-php-ext-enable swoole

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
      && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
      && apt-get update -yqq \
      # Install Dependencies
      && ACCEPT_EULA=Y apt-get install -y libssl-dev unixodbc unixodbc-dev libgss3 odbcinst msodbcsql17 \
      && pecl install pdo_sqlsrv sqlsrv \
      && docker-php-ext-enable pdo_sqlsrv sqlsrv \
      && sed -i -E 's/(CipherString\s*=\s*DEFAULT@SECLEVEL=)2/\11/' /etc/ssl/openssl.cnf \
      && php -m | grep -q 'pdo_sqlsrv' \
      && php -m | grep -q 'sqlsrv'

RUN set -xe && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get remove -y $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && rm -rf /app \
    && mkdir /app

ADD hyperf.ini /usr/local/etc/php/conf.d/hyperf.ini

WORKDIR /app