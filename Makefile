setup:
	cp .env.example .env

start:
	docker-compose up -d

stop:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d --build

restart-php:
	docker-compose stop php && docker-compose up -d

laravel-setup:
	docker-compose exec php composer install
	docker-compose exec php php artisan key:generate

laravel-migrate:
	docker-compose exec php php artisan migrate

test-loan:
	docker-compose exec php php artisan test --filter LoanTest

fresh:
	docker-compose exec php php artisan migrate:fresh

test-sentry:
	docker-compose exec php php artisan sentry:test

test-code-loan:
	composer cs-check

