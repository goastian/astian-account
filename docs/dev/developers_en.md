# Developers

## Deployment Guide
This guide provides comprehensive, step-by-step instructions for deploying the service in your local development environment.

### Branches

- **main**: Stable branch with the latest release.
- **dev**: Development branch with the latest changes.

### Prerequisites

Ensure the following dependencies are installed:

- **nginx**
- **Composer**
- **Node.js** >= 22.17.1
- **Git**
- **PHP** >= 8.3.0
Required PHP extensions:

```sh
    php83-fpm \
    php83-opcache \
    php83-cli \
    php83-mbstring \
    php83-xml \
    php83-curl \
    php83-pecl-redis \
    php83-pecl-memcached \
    php83-pcntl \
    php83-posix \
    php83-pdo \
    php83-pdo_pgsql \
    php83-zip \
    php83-tokenizer \
    php83-json \
    php83-phar \
    php83-fileinfo \
    php83-dom \
    php83-session \
    php83-simplexml \
    php83-xmlwriter \
    php83-soap \
    php83-openssl \
    php83-bcmath \
    php83-gd \
    php83-intl \
    php83-sodium
```
### Local Deployment

1. Clone the repository:
    ```sh
    git clone git@github.com:elyerr/oauth2-passport-server.git
    cd oauth2-passport-server
    ```

### Environment Setup

1. Copy the example environment file:
    ```sh
    cp .env.example .env
    ```
2. Configure your `.env` file. Example (using HTTPS with self-signed certificates):

    ```env
    APP_ENV=dev
    APP_KEY= # Generated on first run
    APP_DEBUG=true
    APP_URL=https://auth.elyerr.xyz  # or us your ip and address
    FRONTEND_URL="${APP_URL}"
    ASSET_URL="${APP_URL}"
    SCHEMA_HTTPS=https # https or http

    # LOGS
    LOG_CHANNEL=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    # DATABASE
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=oauth2
    DB_USERNAME=admin
    DB_PASSWORD=admin
    ```

> **Tip:** Generate self-signed certificates using [CA Generator](https://github.com/elyerr/ca-generator).


2. Install dependencies:
    ```sh
    composer install
    ```
    > If you encounter errors, remove `composer.lock` and retry. Ensure all PHP extensions are installed.
3. Initialize system settings:
    ```sh
    php artisan settings:system-start
    ```
4. Create the first user:
    ```sh
    php artisan settings:create-user
    ```

### Configuration

Log in as the admin user and navigate to **Admin → Settings** to configure:

- General
- Session (cookie settings)
- Payment
- Email (event dispatch)
- Routes (enable/disable system routes)
- Redis
- Cache
- Queue
- Filesystem
- Security (production protection)
- Log Viewer

### Running Background Processes

**Custom Artisan Commands:**

- Settings:
    - `settings:channels-upload` — Upload default channels (Broadcasting server coming soon)
    - `settings:countries-upload` — Upload country list
    - `settings:create-user` — Create a user with a selected role
    - `settings:key-generator` — Generate `APP_KEY` for `.env`
    - `settings:roles-upload` — Upload roles
    - `settings:system-start` — Install essential data

- Payments:
    - `payment:charge-recurring` — Enable automatic recurring payments

> Configure payment settings in **Admin → Settings → Payment**.

**Start the queue worker for events, emails, and notifications:**
```sh
php artisan queue:work --tries=3
```

**(Optional) Enable Recurring Payments:**

Before starting recurring payments, ensure the Stripe webhook is running on your local server. To set up the webhook:

1. Start the Stripe webhook listener (replace `<your-machine-ip>` and `<your-system-port>` with your actual values):
    ```sh
    stripe listen --forward-to <your-machine-ip>:<your-system-port>/webhook/stripe
    ```
    > On first run, Stripe will provide a webhook signing secret. Add this key to **Admin → Settings → Payment → Stripe**.

2. In a new terminal, start the recurring payment process:
    ```sh
    php artisan payment:charge-recurring
    ```

**Frontend Development:**

Run the following only if working with JavaScript assets:
```sh
npm run watch
```

### Nginx Proxy Settings

If using a custom domain locally, add the following to your Nginx configuration:

```nginx
server {
    listen 80;
    server_name auth.elyerr.xyz;

    # For self-signed certificates, uncomment and set the correct paths
    # ssl_certificate /ssl/auth.elyerr.xyz.crt;
    # ssl_certificate_key /ssl/auth.elyerr.xyz.key;

    access_log /var/log/nginx/accounts_access.log main;
    error_log /var/log/nginx/accounts_error.log warn;

    location / {
        proxy_pass http://127.0.0.1:8001;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_set_header X-Forwarded-Host $http_x_forwarded_host;
        proxy_set_header X-Forwarded-Port $http_x_forwarded_port;
        proxy_set_header X-Forwarded-AWS-ELB $http_x_forwarded_aws_elb;

        proxy_read_timeout 720s;
        proxy_connect_timeout 720s;
        proxy_send_timeout 720s;

        proxy_buffering on;
        proxy_buffer_size 128k;
        proxy_buffers 4 256k;
        proxy_busy_buffers_size 256k;
        proxy_temp_file_write_size 256k;

        proxy_redirect off;
    }
}
```

