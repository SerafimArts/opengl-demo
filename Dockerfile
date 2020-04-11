FROM php:7.4

RUN apt update

RUN apt install libffi-dev \
    && docker-php-ext-configure ffi --with-ffi \
    && docker-php-ext-install ffi


RUN apt install libsdl2-2.0-0 -y
RUN apt install libsdl2-image-2.0-0 -y
RUN apt install libsdl2-ttf-2.0-0 -y
RUN apt install libglu1 -y

RUN apt install zip -y

RUN apt install git -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /usr/src/myapp

WORKDIR /usr/src/myapp

RUN composer install

CMD ["php", "./app.php"]
