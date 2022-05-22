FROM burnett0/alpine-php8-lumen

COPY src/ /lumen/

WORKDIR /lumen/

RUN composer install

#docker build -t dockerphp .
#docker run  -p 80:80 -d dockerphp

#"curl -H "Authorization: Bearer $(gcloud auth print-identity-token)" "https://us-central1-fullstack-350901.cloudfunctions.net/sendPubSub?name=Felipe&email=fppoducoes@gmail.com&phone=5567999711166"