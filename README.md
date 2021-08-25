# laravel-oauth2
Laravel 8.5 + passport (Oauth 2.0)

# docker
cp .env.example .env
docker-compose up --build
docker-compose run --rm composer create-project laravel/laravel
docker-compose run --rm composer require laravel/passport
