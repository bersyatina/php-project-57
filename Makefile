PORT ?= 8000
start:
	php artisan serve --host 0.0.0.0:$(PORT)

install:
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	npm ci
	npm run build

update db:
	rm database/database.sqlite
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed

test:
	php artisan migrate:rollback
	php artisan migrate
	php artisan test

test-coverage:
	XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

lint:
	composer exec phpcs -- --standard=PSR12 app routes database/seeders

lint-fix:
	composer exec phpcbf -- --standard=PSR12 app routes tests

cache:
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
