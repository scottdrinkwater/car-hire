# EnjoyTravel Tech Test
## Set-up
- Run `./vendor/bin/sail up -d` to bring up Docker.

## Linting 
Run `docker-compose run laravel.test bin/lint`

## Tests
### PHP
Run `docker-compose run laravel.test ./vendor/bin/phpunit`

## If I had more time / other musings
- TEST COVERAGE
- Docs (Swagger)
- OAUTH Authentication / User stored on booking
- More user friendly validation errors
- Pipeline / CI / CD
- Feature tests on controller
- Caching on bookings (kept fresh using events) / cars