FROM nginx:1.25

ENV TZ=UTC

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN apt-get -y update
RUN apt-get install -y \
    curl \
    gnupg
