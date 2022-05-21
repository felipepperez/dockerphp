FROM burnett0/alpine-php8-lumen

COPY src/ /lumen/

WORKDIR /lumen/

CMD bash -c "composer install"

#docker build -t dockerphp .
#docker run -d dockerphp -p 80:80