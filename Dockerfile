FROM burnett0/alpine-php8-lumen

COPY src/ /lumen/

WORKDIR /lumen/

RUN composer install

#docker build -t dockerphp .
#docker run  -p 80:80 -d dockerphp