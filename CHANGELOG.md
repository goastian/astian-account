# Changelog

## 🚀 [v3.0.1]

### 👥 Users
-🔧 Fix and properly define the relationship with the Partner model (belongsTo, hasOne, etc.), ensuring it aligns with the business logic. 


## 🚀 [v3.0.0]

### 🗄️ Database
- 🏷️ Prefixed all table names with `ops_`
- ⚙️ Optimized migration structure and logic
- ❌ Removed `tax` fields from the `transactions` table
- ❌ Removed `stripe_customer_id` from the `users` table
- 🆕 Added new migration to manage multiple payment providers

### 💳 Payment
- 🛠️ Fixed support for multiple payment providers
- 🔒 Fixed issue with forced activation of payment methods

### 👥 Users
- 🐞 Fixed partner registration with referral code

### 🧩 Middleware
- 🔁 Fixed redirect logic for unauthenticated (guest) users

### 🔐 Login
- 🧭 Fixed redirect behavior after first failed login attempt

### 🧰 Services
- 🔄 Moved `Menu` class from `Models` to `Services`
- 🧹 Refactored `Settings` to follow separation of concerns and moved to `Services`


## [v2.0.2]

### ⚡️ Cache

* Added cache support for **user scopes**, **menus**, and **configurations**.
* The section **Admin → Settings → Cache** allows manual management of cached keys.
* Automatic cache invalidation implemented when scopes expire or related data changes.

### 👮‍♂️ isAdmin (Fix)

* Fixed `isAdmin` logic to prevent false positives when a user does not belong to an admin group.
* Now strictly checks against valid groups assigned to the authenticated user.

### 👥 User Groups (Refactor)

* Unified logic for retrieving all user groups, combining both:
    - Directly assigned groups (with or without services).
    - Groups linked through active scopes and services.

### 🚦 Rate Limiting

* Implemented rate limiting on critical routes to improve security.
* Added configurable rate limit settings in **Admin → Settings → Security**.

### 🔐 OAuth2

-   Fixed an issue with updating OAuth2 clients in the client management interface.

### 📄 Log Viewer

* Added an integrated **log viewer** accessible from the admin panel.
* Enables direct viewing of application logs without accessing the server manually.

### ⚙️ Setting (Fix)

* Moved `SCHEMA_HTTPS` from dynamic settings to the environment file for better consistency.
* Fixed deployment issues when running in environments without HTTPS (i.e., HTTP-only setups).

------

## [v2.0.1]

### OAuth2 Enhancements

-   Enhance OAuth2 token validation middleware to strictly verify token integrity and associated client existence, preventing 500 errors when tokens remain active but related clients have been deleted or are missing.

-------

## [v2.0.0]

### Framework & Package Upgrades

-   Laravel upgraded from v10 to v12.
-   Laravel Passport upgraded from v10 to v13.

### OAuth2 Enhancements

-   Added internal grant type for trusted applications.
-   Implemented OpenID Connect support.

### User Notifications

-   Added notification UI with read/unread tabs.
-   Notifications mark as read before opening.
-   Badge indicators and modern design using Quasar.

### User Payment

-   Added new functionality to enable or disable recurring payment for the user

### Account Validation

-   Fixed logic related to registration and email verification.

### Developer Route Controls

-   Ability to dynamically enable/disable developer features for users.

### Removed Features

-   Log Viewer removed from admin panel.

---

## [v1.0.0]

### 👤 Users

-   **Dashboard**: User overview panel.
-   **Profile**: Edit personal information.
-   **Password**: Change password functionality.
-   **Two-Factor Authentication (2FA)**: Security feature for login.
-   **Subscriptions**: Manage active user subscriptions.
-   **Store**: Access to service or product purchases.
-   **Developers**:
    -   **Applications**: Manage registered apps.
    -   **API Functionality**: Access and manage API keys and usage.
-   **Partner Portal**:
    -   Accessible only to users with "partner" status.
    -   Partners benefit from services sold to users who register or purchase using their referral link.
    -   Access is granted after the partner has completed at least one purchase.

---

### 🛠️ Admin Panel

-   **User Management**: View, edit, or deactivate user accounts.
-   **Group Management**: Organize users into groups.
-   **Role Management**: define service roles.
-   **Service Management**: Create and manage available services.
-   **Client Management**: Manage application integrations (OAuth clients).
-   **Broadcast Management**: Configure real-time channels.
-   **Plan Management**: Create and manage subscription plans.
-   **Transaction Management**: View and manage user payments.
-   **Command Terminal**: Execute system-level artisan commands.
-   **Settings**:
    -   **General**: General application configuration.
    -   **Session**: App session management.
    -   **Payment**: Configure payment providers (stripe, offline).
    -   **Email**: Manage email service settings.
    -   **Routes**: Manage internal app routes.
    -   **Redis**: Redis connection configuration.
    -   **Cache**: Cache system settings _(currently inactive)_.
    -   **Queue**: Queue service configuration.
    -   **Filesystem**: Storage driver configuration.
    -   **Security**: Configure CAPTCHA and other security-related settings.
    -   **Logs**: Log viewer interface.
