FROM nginx:1.25.3-alpine-slim

RUN apk add --no-cache curl bash

COPY ./.docker/nginx/snippets /etc/nginx/snippets
COPY ./.docker/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app