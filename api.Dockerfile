FROM webdevops/php-apache-dev:8.3-alpine
WORKDIR /app
COPY ./src/api /app
