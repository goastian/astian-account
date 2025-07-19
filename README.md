# OAuth2 Passport Server

A robust and centralized authorization server built with Laravel and Laravel Passport. It provides secure authentication and authorization for modern applications using both OAuth2 and OpenID Connect protocols. This solution is ideal for handling complex user management and secure integration across diverse client applications.

## Key Features

- **User Management:**  
    Comprehensive administration capabilities including user profiles, roles, and permissions.

- **Configurable Settings:**  
    Intuitive admin panel to manage environment variables, security options, and integrations.

- **Standard Protocol Support:**  
    Implements OAuth2 and OpenID Connect for secure authorization, token management, and federated identity.

- **Admin Dashboard:**  
    Powerful interface for overseeing users, settings, and system operations.

This server is designed to work seamlessly with both microservices and monolithic architectures, supporting any programming language or database for secure connections and authentication.

---

## Resources

- [Documentation](https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home)  
- [API Documentation](https://documenter.getpostman.com/view/5625104/2sB2xBDq6o)  
- [Echo Server](https://gitlab.com/elyerr/echo-server) (coming soon)  
- [Echo Client](https://gitlab.com/elyerr/echo-client-js) (coming soon)

---

## Deployment Guides

- [English Documentation](./docs/deploy_en.md)  
- [Spanish Documentation](./docs/deploy_es.md)

## Developer Guides

- [English Documentation](./docs/deploy_en.md)  
- [Spanish Documentation](./docs/deploy_es.md)

---

## Notes

### Regenerating OAuth2 Keys

To regenerate OAuth2 keys, follow these steps:
1. Access the admin panel terminal.
2. Run the following command:

```bash
php artisan passport:keys --force
```

---

## Payment Methods

### Stripe

- **Webhook (POST):** `https://domain.com/webhook/stripe`
- **Events Handled:**
    - `checkout.session.completed`
    - `payment_intent.payment_failed`
    - `checkout.session.expired`
    - `charge.succeeded`

### Offline Payment

- **Offline:** Supports manual payment methods.

> **Note:** Automatic renewal is enabled for all payment methods except Offline.  
> Configure renewal options through the Admin panel under **Settings → Payment → Renew**.

---

## CAPTCHA Providers

Enhance form security and prevent spam with the following CAPTCHA options:

### hCaptcha

- Privacy-first alternative to reCAPTCHA.
- Generous free usage.
- [Get your site key](https://dashboard.hcaptcha.com/signup)

### Cloudflare Turnstile

- User verification without traditional CAPTCHAs.
- Seamless and user-friendly.
- [Get your site key](https://dash.cloudflare.com/)

### Configuration

To activate your preferred CAPTCHA provider:
1. Navigate to **Admin → Settings → Security**.
2. Select your desired provider (hCaptcha or Turnstile).

The system will automatically render the selected CAPTCHA on frontend forms.

---

## Contact

For more information or assistance, join our community on [Telegram](https://t.me/elyerr).

