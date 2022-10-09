FROM php:8.1

RUN apt update && \
apt upgrade -y && \
apt install -y libglu1 \
libsdl2-ttf-2.0-0 \
libsdl2-image-2.0-0 \
libsdl2-2.0-0 \
libffi-dev \
git && \
docker-php-ext-configure ffi --with-ffi && \
docker-php-ext-install ffi && \
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/myapp

WORKDIR /usr/src/myapp

RUN cd /usr/src/myapp && composer install

CMD ["php", "./app.php"]
