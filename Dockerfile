FROM php
WORKDIR /usr/src/myapp
# RUN apt-get update && apt-get install -y \
#   php-mysql
RUN docker-php-ext-install pdo pdo_mysql
EXPOSE 8000/tcp
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/usr/src/myapp"]