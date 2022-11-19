# Parse-article-symfony-app
Dockerized Symfony App for Parse article to rabbitmq and consuming to MySQL DB

## Specification
  Symfony 5.4
  php 7.4
  node
  rabbitmq 3.8
  MySql 8.0
  Bootstrap
  
## Installation
  - - create 'mysql' and 'rabbitmq' folder, for docker to build into
  - - Please Ensure you have docker running on your system
  - - docker-compose up -d --build
  - - localhost:8080 - to view web app list of articles with pagination
  - - docker-compose run --rm node-service yarn install - To use node to install yarn/npm into the project
  - - docker-compose run --rm node-service yarn add @symfony/webpack-encore --dev : To install webpack
  - - docker-compose run --rm node-service yarn add bootstrap --dev - To install bootstrap
  - - docker-compose run --rm node-service yarn add sass-loader@^12.0.0 sass --dev
  - - docker-compose run --rm node-service yarn add jquery @popperjs/core --dev - To install jquery and popperJS
  - - docker-compose run --rm node-service yarn dev - To populate app.css and app.js in public build
  - - docker exec -it php74-container bash - To access symfony php74 container terminal
  - [PHP74 terminal]() - composer update - updates composer.json
  - [PHP74 terminal]() - php bin/console doctrine:migrations:migrate - this will migrate your db
  

## Running the project
  - - docker exec -it php74-container bash - To access symfony php74 container terminal
  - - http://localhost:8080 - to view web app list of articles with pagination
  - - http://localhost:8080/admin/article/ - to view admin app list of articles with pagination and delete feature
  - [PHP74 terminal]() - symfony console messenger:consume -vv - To open and listen to rabbit mq queue worker
  - [PHP74 terminal]() - php bin/console parse:article - To scrape url for articles and publish



## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Opone Chukwuyenum via [oponechukwuyenum@gmail.com](mailto:oponechukwuyenum@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Parse Article App software licensed under the [MIT license](https://opensource.org/licenses/MIT).
