# Default Dockerfile
#
# @link     https://www.hyperf.io
# @document https://hyperf.wiki
# @contact  group@hyperf.io
# @license  https://github.com/hyperf/hyperf/blob/master/LICENSE

FROM hyperf/hyperf:8.0-alpine-v3.16-swoole
LABEL maintainer="Hyperf Developers <group@hyperf.io>" version="1.0" license="MIT" app.name="Hyperf"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true)

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php* \
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
    # SqlServer环境
    && wget -nv -O /tmp/sqlsrv.tar https://github.com/Microsoft/msphpsql/releases/download/v5.10.1/Alpine315-8.0.tar \
    && tar -xf /tmp/sqlsrv.tar -C /tmp/ \
    && mv /tmp/Alpine315-8.0 /tmp/sqlsrv \
    && mv /tmp/sqlsrv/php_pdo_sqlsrv_80_nts.so /usr/lib/php8/modules/pdo_sqlsrv.so \
    && echo "extension=pdo_sqlsrv.so" > /etc/php8/conf.d/00_pdo_sqlsrv.ini \
    && wget -nv -O /tmp/msodbcsql.apk https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/msodbcsql18_18.1.2.1-1_amd64.apk \
    && apk add --allow-untrusted /tmp/msodbcsql.apk \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www