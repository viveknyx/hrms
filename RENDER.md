# Deploying PeopleOps HRMS on Render

This project includes a Dockerfile for Render Web Services.

## Render service settings

- Environment: Docker
- Branch: your deployment branch
- Health check path: `/login`
- The app listens on Render's `PORT` automatically.

## Required environment variables

Set these in Render's dashboard:

```text
APP_NAME=PeopleOps HRMS
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
APP_URL=https://your-service-name.onrender.com
LOG_CHANNEL=stderr
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
RUN_MIGRATIONS=true
CACHE_LARAVEL=true
```

Generate `APP_KEY` locally with:

```bash
php artisan key:generate --show
```

## Database

For a MySQL database, set:

```text
DB_CONNECTION=mysql
DB_HOST=your-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

For Render PostgreSQL, set:

```text
DB_CONNECTION=pgsql
DB_HOST=your-render-postgres-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

The container runs `php artisan migrate --force` on startup when `RUN_MIGRATIONS=true`.
It does not run seeders automatically in production.
