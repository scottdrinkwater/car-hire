# EnjoyTravel Tech Test
## Set-up
- Run `./vendor/bin/sail up -d` to bring up Docker.
- Run migrations: `docker-compose run app php artisan migrate` (ideally add to dockerfile)
- Seed data: `docker-compose run app php artisan db:seed`

## Linting 
Run `docker-compose run app bin/lint`

## Tests
### PHP
Run `docker-compose run app ./vendor/bin/phpunit`

## If I had more time / other musings
- TEST COVERAGE
- Docs (Swagger)
- OAUTH Authentication / User stored on booking
- More user friendly validation errors
- Pipeline / CI / CD
- Feature tests on controller
- Caching on bookings (kept fresh using events) / cars