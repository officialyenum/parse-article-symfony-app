# Parse-article-symfony-app
Dockerized Symfony App

## Specification
  Symfony 5.4
  php 7.4
  node
  rabbitmq 3.8
  MySql 8.0
  Bootstrap
## Installation
  - - docker compose up -d build
  - - localhost:8080 - to view web app list of articles with pagination
  - - docker compose up -d build
  - - docker compose up -d build
  - - docker exec -it php74-container bash - To access symfony php app
  - php74 terminal - composer update - updates composer.json
  - php74 terminal - symfony console messenger:consume -vv - To open rabbit mq queue worker
  - php74 terminal - php bin/console parse:article - To scrape url for articles and publish to rabbit mq
  - docker-compose run --rm node-service yarn install - To install node and yarn/npm
  - docker-compose run --rm node-service yarn add bootstrap --dev - To install bootstrap
  
