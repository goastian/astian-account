# Oauth2 passport server

A robust, centralized authorization server built with Laravel and Laravel Passport, designed to provide secure authentication and authorization for modern applications. This server supports both OAuth2 and OpenID Connect protocols, enabling seamless integration with a wide range of clients and services.

Key features include:

- **User Management:** Comprehensive user administration with roles, permissions, and profile management.
- **Settings Zone:** Flexible system configuration through an intuitive admin panel, allowing you to manage environment variables, security options, and integrations.
- **OAuth2 & OpenID Connect:** Standards-compliant support for secure authorization flows, token management, and federated identity.
- **Admin Dashboard:** Powerful administrative interface for managing users, settings, and system operations.

This server is ideal for microservices and monolithic architectures, allowing applications in any programming language or database to connect and authenticate securely. Developers can leverage its features to build scalable, secure, and unified systems with centralized user and settings management.

---

## Resources

-   [Documentation](https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home)
-   [API Documentation](https://documenter.getpostman.com/view/5625104/2sB2xBDq6o)
-   [Echo Server](https://gitlab.com/elyerr/echo-server)
-   [Echo Client](https://gitlab.com/elyerr/echo-client-js)

---

# 🚀 Deploy & First User Setup

This project uses Docker and Laravel for OAuth2 authentication. Follow the steps below to deploy the production environment and create the first user.

-----

## 🔑 Environment Configuration

Before deployment, make sure to copy the environment file and configure the necessary variables:

```bash
cp .env.example .env
```
## Basic Configuration Guide
- Application environment (e.g., local, staging, production)
```
APP_ENV=production
```
- This keys generate when the docker start o execute `php artisan settings:system-start`
```
APP_KEY=
```

- debug mode (true or false), Enables detailed error output (not recommended in production)
```
APP_DEBUG=true
```

- The base URL where the app is running. If the host uses a port other than 80 or 443, include it (e.g. http://example.com:8006)
```
APP_URL=http://localhost:8006
FRONTEND_URL="${APP_URL}"      # URL for the frontend; defaults to -APP_URL
ASSET_URL="${APP_URL}"                   # URL used to load static assets (CSS, JS, etc.)
SCHEMA_HTTPS=https  # Possible values (http or https) 
```

- Logging configuration 
```
LOG_CHANNEL=daily                         # Logging method (recommended: daily)
LOG_DEPRECATIONS_CHANNEL=null            # Channel for logging deprecated features (set to null to disable)
LOG_LEVEL=debug                           # Log level (e.g., debug, info, warning, error)
```

- Database Configuration
```
DB_CONNECTION=pgsql                       # Database driver (pgsql, mysql, sqlite, etc.)
```

- For Docker environments, use the service name as the host (e.g., "db"). If connecting to an external DB, provide the IP address.
```
DB_HOST=192.168.1.33
```

- Default PostgreSQL port is 5432. Change this only if your DB is configured to use a different port.
```
DB_PORT=5432
DB_DATABASE=testdb                        # Name of your database
`DB_USERNAME=admin  # Database username
```
- Use a secure and strong password in production
```
DB_PASSWORD=admin
```

- Additional Configuration : All other system configurations can be managed via the Admin panel under "Settings".
- After finishing your configuration, use the "Apply & Cache" (green button) to cache settings for better performance.

Then, edit the .env file with your specific settings.

----

## Deploy to Production

Run the following command to deploy the application 
- in a production environment:
```bash
./deploy-prod.sh
```
- In development environment
```bash
./deploy-dev.sh
```

## 👤 Create the First User

To create an initial user in the application, execute the following command inside the container:

- In production environment 
```bash
docker exec -it oauth2-server-app-1 php artisan settings:create-user
```
- In Development environment
```bash
docker exec -it oauth2-server-dev-app-1 php artisan settings:create-user
```

## Proxy settings Nginx server

```bash
server {

    listen 80;
    server_name accounts.server.org;

    # Logging
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

# Notes

## Re-generate OAuth2 keys

-   Go to admins and choose terminal and type the next command

```bash
php artisan passport:keys --force
```

## Payment Methods

-   **Stripe**: Third-party payment method

    -   **Webhook (POST)**: `https://domain.com/webhook/stripe`
    -   **Events handled**:
        -   `checkout.session.completed`
        -   `payment_intent.payment_failed`
        -   `checkout.session.expired`
        -   `charge.succeeded`

-   **Offline**: Offline payment method

> **Note:** Automatic renewal support has been added for all payment methods **except Offline**.  
> You can configure renewal options in the Admin panel under **Settings → Payment → Renew**.

This project supports the following CAPTCHA providers to help protect your forms from spam and automated abuse:

## 🛡️ CAPTCHA Providers

### [hCaptcha](https://www.hcaptcha.com/)

-   Privacy-first alternative to reCAPTCHA
-   Generous free usage
-   [Get site key](https://dashboard.hcaptcha.com/signup)

---

### [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/)

-   CAPTCHA-free user verification
-   Seamless and user-friendly
-   [Get site key](https://dash.cloudflare.com/)

---

### ⚙️ Configuration

To configure the active CAPTCHA provider:

> Go to **Admin → Settings → Security**, and select your preferred CAPTCHA provider (hCaptcha or Turnstile).

Once configured, the system will automatically load and render the selected provider in the frontend forms.

## Contact

For direct contact, visit [Telegram](https://t.me/elyerr).