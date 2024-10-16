FROM phpswoole/swoole:5.1-php8.3-alpine

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true)

RUN wget https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/msodbcsql18_18.1.2.1-1_amd64.apk && \
  wget https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/mssql-tools18_18.1.1.1-1_amd64.apk && \
  apk add --allow-untrusted msodbcsql18_18.1.2.1-1_amd64.apk && \
  apk add --allow-untrusted mssql-tools18_18.1.1.1-1_amd64.apk && \
  apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS unixodbc-dev && \
  pecl install pdo_sqlsrv && \
  apk add php-pdo && \
  docker-php-ext-enable pdo_sqlsrv && \
  apk del .phpize-deps git && \
  rm msodbcsql18_18.1.2.1-1_amd64.apk && \
  rm mssql-tools18_18.1.1.1-1_amd64.apk

#RUN #apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS unixodbc-dev && \
#    docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr && \
#    docker-php-ext-install pdo_odbc

RUN apk add --update libzip-dev libmcrypt-dev libxml2-dev icu-dev postgresql-dev libmemcached-dev &&\
    apk add --update --virtual build-dependencies build-base gcc wget autoconf && \
    apk add composer bash bash-completion &&\
    apk add php-xml php-dom php-tokenizer php-xmlwriter php-redis &&\
    apk del build-dependencies &&\
    rm -rf /var/cache/apk/*

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    && php --ri xml \
    && php --ri xmlwriter \
    && php --ri json \
    && php --ri dom \
    && php --ri pcntl \
    && php --ri tokenizer \
    && php --ri pdo_sqlsrv \
#    && php --ri pdo_odbc \
    #  ---------- some config ----------
    && cd /etc/php83 \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

ADD hyperf.ini /usr/local/etc/php/conf.d/hyperf.ini

WORKDIR /app