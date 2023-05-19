FROM php:8.0.13-apache

# update base image
RUN apt-get update && apt-get upgrade -y && apt-get autoremove -y && apt-get autoclean -y

# extension
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
