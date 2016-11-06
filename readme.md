# Project-Web: Bookshare.ga

## About

Bookshare.ga is een lokale en sociale manier om boeken te delen, gratis, tegen betaling of in leen.

More info: https://docs.google.com/document/d/1JL2yXR3Gj7_qodSnezfVzFqSwn0yfbTV9qT0aPnBxGw/edit?usp=sharing

## Deployement

1. `git clone` de repo
2. `composer install`
3. Maak een .env bestand aan gebasseerd op `.env.example`
4. `php artisan key:generate` om een key in toe te voegen aan .env
5. `php artisan migrate` om de database in te stellen
6. Cronjob: `* * * * * php /path/to/site/artisan schedule:run >> /dev/null 2>&1`
7. Maak op de Google API Console een API key aan voor de Books API en Distance Matrix API en zet deze informatie in het .env bestand

## Build

1. Zie deployement
2. `npm install` (eventueel met `--no-bin-links` op Windows)
3. `gulp` om assets te packen naar `/public`