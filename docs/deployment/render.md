# Deploy do StockFlow no Render

## Overview

The StockFlow application is deployed to Render as a Docker-based web service.

The production environment uses:

- PHP 8.4
- Laravel
- PHP-FPM
- Nginx
- Docker
- SQLite
- Render
- GitHub Actions for CI

The application runs Nginx and PHP-FPM inside the same container. Render exposes the HTTP service through port `10000`.

## Deployment Architecture

The production request flow is:

```text
Client
  │
  ▼
Render
  │
  ▼
Nginx :10000
  │
  ▼
PHP-FPM :9000
  │
  ▼
Laravel
  │
  ▼
SQLite
```

Nginx is responsible for receiving HTTP requests and forwarding PHP requests to PHP-FPM.

PHP-FPM listens internally on:

```text
127.0.0.1:9000
```

Nginx listens on:

```text
10000
```

Only the HTTP service needs to be exposed externally.

## Docker Configuration

The application uses `php:8.4-fpm` as the base image.

The Docker image installs the system dependencies required by Laravel, Nginx, PostgreSQL support, SQLite and ZIP:

```Dockerfile
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nginx \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_sqlite \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
```

Both `pdo_pgsql` and `pdo_sqlite` are installed because the application supports the corresponding database drivers.

Composer dependencies are installed during the image build:

```Dockerfile
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts
```

After the application source is copied into the image, the SQLite database is created and the Laravel migrations are executed:

```Dockerfile
RUN touch database/database.sqlite

RUN php artisan migrate --force
```

Laravel package discovery is then executed:

```Dockerfile
RUN php artisan package:discover --ansi
```

## Nginx

The Nginx configuration is stored in:

```text
docker/nginx/default.conf
```

Nginx serves the Laravel `public` directory:

```nginx
root /var/www/html/public;
```

Requests that do not correspond to an existing file are forwarded to Laravel's front controller:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

PHP requests are forwarded to PHP-FPM:

```nginx
location ~ \.php$ {
    include fastcgi_params;

    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;

    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

## Render Port

Render needs to reach the HTTP server running inside the container.

The Docker image exposes port `10000`:

```Dockerfile
EXPOSE 10000
```

Nginx therefore listens on the same port:

```nginx
listen 10000;
listen [::]:10000;
```

PHP-FPM does not need to be exposed to Render because it is an internal process:

```text
Nginx → 127.0.0.1:9000 → PHP-FPM
```

The externally accessible service is Nginx on port `10000`.

## Container Startup

The container starts both PHP-FPM and Nginx:

```Dockerfile
CMD ["sh", "-c", "nginx -t && php-fpm -D && nginx -g 'daemon off;'"]
```

The startup sequence is:

1. Validate the Nginx configuration.
2. Start PHP-FPM in the background.
3. Start Nginx in the foreground.

Keeping Nginx in the foreground allows the container to remain alive and lets Render monitor the running web service.

## Laravel Environment Variables

Production-specific configuration is provided through Render Environment Variables rather than committing `.env` files to the repository.

At minimum, the application requires the appropriate Laravel application configuration, including:

```text
APP_ENV
APP_KEY
APP_DEBUG
APP_URL
LOG_CHANNEL
```

When a database configuration is required through environment variables, the corresponding `DB_*` variables must also be configured in Render.

Sensitive values must not be committed to the repository.

## Logging

The production container sends Laravel logs to `stderr`:

```text
LOG_CHANNEL=stderr
```

This allows Render to collect Laravel application errors directly in the service logs.

This is particularly useful for production debugging because the Render free tier does not provide the same interactive shell capabilities available on higher tiers.

For example, an application exception can be diagnosed directly through the Render Logs without requiring access to:

```text
storage/logs/laravel.log
```

## SQLite Permissions

The application currently uses SQLite.

The SQLite database is created during the Docker image build:

```Dockerfile
RUN touch database/database.sqlite
```

Laravel migrations are then executed against this database:

```Dockerfile
RUN php artisan migrate --force
```

Because PHP-FPM runs as `www-data`, the Laravel runtime must have write permission to the SQLite database.

The `database` directory is therefore included in the writable directories:

```Dockerfile
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database
```

This is required because SQLite is not only read from during API requests. Operations such as:

```text
POST /api/products
PUT /api/products/{id}
DELETE /api/products/{id}
```

must write to the database.

Without the correct permissions, read operations can work while write operations fail with:

```text
SQLSTATE[HY000]: General error: 8
attempt to write a readonly database
```

## CI and Deployment

The deployment branch is validated through GitHub Actions before changes are merged into `main`.

The CI pipeline validates the application and Docker build.

The deployment workflow is:

```text
Feature/Fix Branch
       │
       ▼
GitHub Pull Request
       │
       ▼
GitHub Actions
       │
       ├── Tests
       └── Docker Build
       │
       ▼
Merge into main
       │
       ▼
Render Deployment
       │
       ▼
Production
```

This prevents a change from being merged without passing the project's automated validation.

## Production Troubleshooting

### Application returns 404

First verify that the request reaches the Render service.

If Render reports that no server is handling the request, verify:

- Nginx is running.
- Nginx listens on port `10000`.
- The Docker image exposes port `10000`.
- The Render service is configured as a web service.
- The Nginx configuration is being copied to the expected location.

### Application returns 500

Check the Render service logs.

With:

```text
LOG_CHANNEL=stderr
```

Laravel exceptions are available directly through the Render logs.

The application stack trace should be used to identify whether the failure occurs in:

- routing;
- validation;
- application services;
- database access;
- filesystem access;
- or another Laravel component.

### GET works but POST fails

This can indicate a write-permission problem with SQLite.

For example:

```text
GET /api/products
        ↓
SQLite read
        ↓
works

POST /api/products
        ↓
SQLite INSERT
        ↓
requires write permission
        ↓
fails if database is read-only
```

Check that the `database` directory belongs to the runtime user:

```Dockerfile
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database
```

## Current Database Strategy

SQLite is currently used for the deployed application.

This approach is suitable for the current StockFlow development stage because it keeps the deployment simple and avoids introducing an additional managed database service while the application is still being developed.

However, SQLite is stored inside the container filesystem. Container filesystems should not be treated as durable production data storage.

As the StockFlow application evolves toward a persistent production environment, the database strategy should be revisited, with PostgreSQL being the natural next step.

The application already includes the `pdo_pgsql` PHP extension to support this transition.

## Deployment Checklist

Before merging deployment-related changes:

- [ ] GitHub Actions passes.
- [ ] Docker image builds successfully.
- [ ] Nginx configuration is valid.
- [ ] PHP-FPM starts successfully.
- [ ] Render detects the web service on port `10000`.
- [ ] Required environment variables are configured.
- [ ] Laravel migrations execute successfully.
- [ ] `storage`, `bootstrap/cache` and `database` are writable by `www-data`.
- [ ] `GET /api/products` works.
- [ ] `POST /api/products` works.
- [ ] Production logs are available through Render.

## Related Documentation

- [Project README](../../README.md)
- [Laravel Documentation](https://laravel.com/docs)
- [Render Documentation](https://render.com/docs/web-services)
