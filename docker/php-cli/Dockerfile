FROM php:8.2-zts-bookworm

ENV SWOOLE_VERSION=5.1.2

# persistent / runtime deps
ENV PHPIZE_DEPS \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    git \
    re2c

ENV ACCEPT_EULA=Y

RUN apt-get update \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
        gnupg2 \
        libcurl4 \
        wget \
        zlib1g-dev \
        libzip-dev \
        libldap-common \
        libldap2-dev \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

RUN docker-php-ext-install \
            ldap \
            pcntl \
            bcmath \
            zip

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
      && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
      && apt-get update -yqq \
      # Install Dependencies
      && ACCEPT_EULA=Y apt-get install -y libssl-dev unixodbc unixodbc-dev libgss3 odbcinst msodbcsql17 \
      && pecl install sqlsrv-5.12.0 \
      && pecl install pdo_sqlsrv-5.12.0 \
      && docker-php-ext-enable pdo_sqlsrv sqlsrv \
      && php -m | grep -q 'pdo_sqlsrv' \
      && php -m | grep -q 'sqlsrv'

COPY openssl.cnf /etc/ssl/openssl.cnf

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

RUN set -xe && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get remove -y $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && rm -rf /app \
    && mkdir /app

ADD hyperf.ini /usr/local/etc/php/conf.d/hyperf.ini

WORKDIR /app