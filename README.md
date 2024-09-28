# README

## DOCKER DEPLOY DEV
- docker-compose -f docker-compose.dev.yml up -d --build

## SYMFONY DEPLOY DEV
### Install composer dependences
- docker exec -it veci_php sh
- composer install

### Configure JWT
- docker exec -it veci_php sh
- php bin/console lexik:jwt:generate-keypair

## SYMFONY WORK DEV
- docker exec -it veci_php sh
- install: npm i
- build: npm run dev
- watch: npm run watch

API
Habilitar o deshabilitar el paginado
GET /books?pagination=false: disabled
GET /books?pagination=true: enabled
Item por paginado
GET /books?itemsPerPage=20