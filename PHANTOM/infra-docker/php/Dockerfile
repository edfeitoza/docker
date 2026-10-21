# Base Debian (glibc): o Oracle Instant Client não é suportado em Alpine (musl)
FROM php:8.3-fpm-bookworm

ARG TARGETARCH
ENV ORACLE_HOME=/opt/oracle/instantclient \
    LD_LIBRARY_PATH=/opt/oracle/instantclient \
    NLS_LANG=BRAZILIAN PORTUGUESE_BRAZIL.AL32UTF8

# 1) Dependências de sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
        libaio1 libicu-dev libzip-dev unzip curl ca-certificates \
 # 2) Oracle Instant Client (basic + sdk) — x64 ou arm64 conforme o host
 && case "$TARGETARCH" in arm64) IC_ARCH=linux-arm64 ;; *) IC_ARCH=linuxx64 ;; esac \
 && mkdir -p /opt/oracle && cd /opt/oracle \
 && curl -fsSLo basic.zip "https://download.oracle.com/otn_software/linux/instantclient/instantclient-basic-${IC_ARCH}.zip" \
 && curl -fsSLo sdk.zip   "https://download.oracle.com/otn_software/linux/instantclient/instantclient-sdk-${IC_ARCH}.zip" \
 && unzip -q basic.zip && unzip -qo sdk.zip && rm -f *.zip \
 && mv instantclient_* instantclient \
 && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf && ldconfig \
 # 3) Extensões PHP (pdo_oci compilado igual ao pdo_mysql)
 && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient \
 && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli pdo_oci intl zip opcache \
 # 4) Limpeza
 && apt-get purge -y unzip && apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/zz-custom.ini

WORKDIR /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
