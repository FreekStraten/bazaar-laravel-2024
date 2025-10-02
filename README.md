# Whitelabel Marketplace — Auctions & Rentals (Laravel)

![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel&logoColor=white)
![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Status](https://img.shields.io/badge/status-archived-inactive)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](./LICENSE)

**Rol:** Solo · **Jaar:** 2025

Whitelabel **marktplaats** met **veilingen** en **verhuur**. Bedoeld voor particulieren én bedrijven, inclusief theming per klant (whitelabel), i18n (NL/EN), CSV‑import en QR‑codes op advertenties.

---

## Tech stack
Laravel 11 · PHP 8.2+ · MySQL/MariaDB · Blade/Tailwind · (optioneel) Laravel Sanctum (API tokens) · PHP‑Spreadsheet (CSV) · QR code package

## Highlights (scope)
- **Accounts & rollen** – registratie als gebruiker, particuliere of zakelijke adverteerder.
- **Whitelabel/theming** – per klant eigen stijl (kleuren/logo) en eigen landingspagina/URL.
- **Landing page builder** – componenten zoals *uitgelichte advertenties*, tekstblok, afbeelding.
- **Advertenties** – kopen, **veilingen**, **verhuur** (met agenda’s voor beschikbaarheid/retour).
- **Favorieten & historie** – gebruikers kunnen favoriete advertenties en aankoop-/huurhistorie zien.
- **CSV‑import** – in bulk advertenties importeren.
- **QR‑codes** – elke advertentie krijgt een QR‑code om snel te delen.
- **API** – beveiligde endpoints (bijv. via **Sanctum**) voor externe apps of klant‑specifieke feeds.
- **i18n** – minimaal **Nederlands** en **Engels** (Laravel translations).

> Business rules (selectie): max. 4 biedingen/advertenties/verhuur‑advertenties per gebruiker; bij retourneren slijtage berekenen + verplichte foto‑upload; advertenties kunnen bundelen (bijv. kettingzaag + olie).

---

## Demo
*(optioneel)* Plaats hier je screenshots/gif uit `/docs/` en een live demo‑link als je die hebt.

![Screens](docs/demo.gif)

---

## Snel starten

### Vereisten
- **PHP 8.2+**, **Composer**, **Node 18+** (of 20+), **npm** of **pnpm**
- **MySQL 8+** of **MariaDB 10.6+**

### Installatie
```bash
git clone https://github.com/FreekStraten/marketplace-laravel-2025.git
cd marketplace-laravel-2025

# 1) Dependencies
composer install
npm ci   # of: npm install

# 2) Env & app key
cp .env.example .env
php artisan key:generate

# 3) Database (vul DB_* in .env)
php artisan migrate --seed

# 4) Storage symlink (uploads/qr/…)
php artisan storage:link

# 5) Assets
npm run dev      # voor ontwikkeling
# npm run build  # voor productie

# 6) Start
php artisan serve
# app draait op http://127.0.0.1:8000
```

### Optioneel (API‑beveiliging met Sanctum)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```
Registreer tokens via de UI of een route; bescherm API‑routes met `auth:sanctum` middleware.

### Optioneel (taalbestanden)
```
resources/lang/nl/*.php
resources/lang/en/*.php
```
Gebruik `__('messages.key')` in Blade/Controllers. `config/app.php` → `locale` en `fallback_locale`.

---

## Belangrijke routes (indicatief)
- `/` – homepage met laatste/uitgelichte advertenties
- `/auth/*` – registratie & login (gebruiker/particulier/zakelijk)
- `/dashboard` – adverteerder: advertenties, veilingen, verhuur agenda, CSV‑import
- `/api/*` – beveiligde API‑endpoints (bijv. klant‑specifieke feed)

*(Pas aan naar je daadwerkelijke routes.)*

---

## Architecture at a glance
- **Domains**: Users, Listings (koop/veiling/verhuur), Orders, Tenants (whitelabel), Pages (builder).
- **Layers**: Controllers → Services/Actions → Repositories/Eloquent Models.
- **Security**: policies/guards, Sanctum tokens voor API.
- **i18n**: Laravel translations (NL/EN).
- **Files**: uploads in `storage/app/public` → via `storage:link` publiek.
- **Extensibility**: nieuwe landing‑page component = nieuwe ViewComponent + config; nieuwe feed/API = aparte route/resource.

---

## Configuratie (.env – voorbeeld)
```env
APP_NAME="Marketplace"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=secret

# MAIL_* als je mails/contracten wilt versturen
# QUEUE_CONNECTION=database  # als je jobs gebruikt
```

---

## Troubleshooting

- **`vite not recognized`** → run eerst `npm install`.
- **`Could not open input file: artisan`** → run `composer install` zodat `vendor/autoload.php` bestaat.
- **`curl_setopt(): Unable to create temporary file` bij composer** → zorg dat `TEMP/TMP` schrijfbaar is of update Composer (`composer self-update --update-keys`).
- **Database error `Unknown database`** → maak eerst een schema aan in MySQL Workbench (schema = database) en zet de naam in `.env`.
- **`Vite manifest not found`** → start in aparte terminal `npm run dev` of run `npm run build`.


## Credits
Opdrachtcontext: whitelabel marketplace met veilingen & verhuur. Code & documentatie © 2025 Freek Straten.
