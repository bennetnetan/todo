# Todo Demo (Laravel 12)

Small authenticated todo app built with Laravel 12 and Breeze. Users can add, edit, complete, and delete tasks. A demo login is bundled so reviewers can explore without creating an account.

## Demo Credentials

- Email: `demo@example.com`
- Password: `password`
- Quick fill: on the login page, click "Fill & use" to auto-populate the form.

## Features

- Authenticated CRUD for todos with completion toggle
- Starter tasks seeded for first run
- Breeze-powered auth scaffolding

## Local Setup

1. Copy `.env.example` to `.env` and configure your database (MySQL by default; SQLite also works).
2. Install PHP dependencies: `composer install`.
3. Install frontend deps: `npm install`.
4. Generate app key: `php artisan key:generate`.
5. Run migrations and seed the demo data: `php artisan migrate --seed`.
6. Start the dev server: `php artisan serve` and visit `http://localhost:8000`.

## Seeding Demo Data

Running `php artisan migrate --seed` creates the demo user above and three starter todos. Rerunning the seeder is idempotent for the demo tasks.

## License

MIT
