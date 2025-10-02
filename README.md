# Bazaar (Laravel 2024)
**Status:** In ontwikkeling · **Rol:** Solo · **Jaar:** 2024

## Tech stack
Laravel · PHP 8.1 · Composer · MySQL · Node/Vite

## Snel starten
```bash
# Dependencies installeren
composer install
npm install

# .env klaarmaken
cp .env.example .env
php artisan key:generate

# Database klaarmaken
# Maak een lege schema 'bazaar' in MySQL Workbench of via CLI
php artisan migrate --seed

# Storage symlink voor uploads
php artisan storage:link

# Start servers
npm run dev       # frontend in aparte terminal
php artisan serve # backend op http://127.0.0.1:8000
```

## Troubleshooting

- **`vite not recognized`** → run eerst `npm install`.
- **`Could not open input file: artisan`** → run `composer install` zodat `vendor/autoload.php` bestaat.
- **`curl_setopt(): Unable to create temporary file` bij composer** → zorg dat `TEMP/TMP` schrijfbaar is of update Composer (`composer self-update --update-keys`).
- **Database error `Unknown database`** → maak eerst een schema aan in MySQL Workbench (schema = database) en zet de naam in `.env`.
- **`Vite manifest not found`** → start in aparte terminal `npm run dev` of run `npm run build`.

## License
MIT
