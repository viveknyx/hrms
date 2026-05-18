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
FORCE_HTTPS=true
LOG_CHANNEL=stderr
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
RUN_MIGRATIONS=false
CACHE_LARAVEL=true
```

Generate `APP_KEY` locally with:

```bash
php artisan key:generate --show
```

## Database

For Supabase on Render, use the PostgreSQL pooler connection details from your Supabase project settings. The direct host (`db.your-project-ref.supabase.co`) can fail on Render with `Network unreachable` because it may resolve to IPv6.

```text
DB_CONNECTION=pgsql
DB_HOST=your-supabase-pooler-host
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
DB_PASSWORD=your-supabase-database-password
DB_SSLMODE=require
```

In Supabase, copy this from **Project Settings -> Database -> Connection string -> Transaction pooler**. Keep `DB_CONNECTION=pgsql` and `DB_SSLMODE=require`.

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
DB_SSLMODE=require
```

The container runs `php artisan migrate --force` on startup only when `RUN_MIGRATIONS=true`.
It does not run seeders automatically in production.

For this project, keep `RUN_MIGRATIONS=false` on Render after you have already migrated and seeded Supabase locally.

Use `SESSION_DRIVER=file` unless you add Laravel's sessions table migration.

To create the HRMS schema and realistic demo records in a fresh Supabase database from your local machine, point your local `.env` at Supabase and run:

```bash
php artisan migrate:fresh --seed
```

Only run `migrate:fresh --seed` on an empty database because it drops existing tables.
