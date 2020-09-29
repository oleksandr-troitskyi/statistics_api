start: # start a project
	sudo chmod -R 777 docker/nginx/log docker/php/log docker/db/mysql/data docker/db/mysql/log
	docker-compose up -d

stop: # down a project
	docker-compose down

restart: # restart a project
	docker-compose restart

composer-install: # install composer
	docker-compose exec php-bundle composer install

build: # project bootstrap
	docker-compose build
	docker-compose up -d
	docker-compose exec php-bundle composer install
	sudo chmod -R 777 docker/nginx/log docker/php/log docker/db/mysql/data docker/db/mysql/log
	docker-compose exec php-bundle php bin/console doctrine:migrations:migrate
	docker-compose exec php-bundle php bin/console doctrine:schema:create --env=test

tests:
	docker-compose exec php-bundle bin/phpunit

fill-tables:
	docker-compose exec php-bundle php bin/console app:fill-tables

