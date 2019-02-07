FROM php
WORKDIR /usr/src/myapp
RUN docker-php-ext-install pdo pdo_mysql
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/usr/src/myapp"]