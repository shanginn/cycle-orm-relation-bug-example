FROM ghcr.io/shanginn/spiral-docker-image-base:master

WORKDIR /app
VOLUME /app

RUN apk add watchexec --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community/ \
  && install-php-extensions xhprof xdebug

RUN chown som:som /app /opt

USER som
