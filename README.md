# Authorization Server

Centralized authorization server using OAuth-server through Laravel and Laravel Passport as an abstraction or bridge layer. It implements broadcasting using [Echo Server](https://gitlab.com/elyerr/echo-server) and [Echo Client](https://gitlab.com/elyerr/echo-client-js).

This server was developed to facilitate the creation of microservices applications that can easily connect to the main server as if they were a unified system. With this authentication and authorization server, you can develop microservices or monolithic applications in any programming language and database manager. This enables developers to build applications in the language they are most proficient in, allowing for the creation of more complex applications.

---

## Resources

-   [Official Documentation](https://gitlab.com/elyerr/outh2-passport-server/-/wikis/home)
-   [Echo Server](https://gitlab.com/elyerr/echo-server)
-   [Echo Client](https://gitlab.com/elyerr/echo-client-js)

# 🚀 Deploy & First User Setup

This project uses Docker and Laravel for OAuth2 authentication. Follow the steps below to deploy the production environment and create the first user.

## 🔑 Environment Configuration

Before deployment, make sure to copy the environment file and configure the necessary variables:

```bash
cp .env.example .env
```

Then, edit the .env file with your specific settings.

## Deploy to Production

Run the following command to deploy the application in a production environment:

```bash
./deploy-prod.sh
```

## 👤 Create the First User

To create an initial user in the application, execute the following command inside the container:

```bash
docker exec -it oauth2-server-app-1 php artisan settings:create-user
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
        proxy_pass http://127.0.0.1:8005;
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
php artisan passport:install --force
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

---

## License

This project is licensed under the GNU Affero General Public License v3.0. See the [LICENSE](./LICENSE) file for details.

---

## Contact

-   Want to collaborate, contribute, or ask questions?
-   For direct contact, visit [Telegram](https://t.me/elyerr).
