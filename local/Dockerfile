FROM public.ecr.aws/docker/library/php:8.3-cli-alpine@sha256:25bcb4f1ce4f13651c0f89a6a2db2b14ac5a75555d3553fa03c83dff6d6966a8 AS base

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN addgroup --gid "${GROUP_ID}" "app" && \
    adduser \
    --disabled-password \
    --gecos "" \
    --home "/app-home" \
    --ingroup "app" \
    --uid "${USER_ID}" \
    -s "/bin/sh" \
    "app"

COPY --from=composer/composer:2.10.2-bin /composer /usr/bin/composer

RUN apk add --no-cache git

COPY php_conf.ini /usr/local/etc/php/conf.d/999-custom.ini

WORKDIR /app

FROM base AS dev

ARG XDEBUG_VERSION="3.5.1"
RUN apk add --no-cache autoconf file g++ gcc libc-dev make pkgconf re2c linux-headers \
  && apk add --no-cache bash git \
  && pecl install "xdebug-${XDEBUG_VERSION}" pcov \
  && docker-php-ext-enable pcov \
  && apk del autoconf file g++ gcc libc-dev make pkgconf re2c linux-headers

COPY xdebug-entrypoint.sh /usr/local/bin/docker-php-xdebug-entrypoint

RUN touch /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/bin/noxdebug \
    && chown app:app /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/bin/noxdebug

USER app
