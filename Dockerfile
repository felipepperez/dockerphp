FROM burnett0/alpine-php8-lumen

COPY src/ /lumen/

#docker build -t dockerphp .
#docker run -d dockerphp -p 80:80