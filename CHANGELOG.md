# Changelog

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

- Added new functionality to enable or disable recurring payment for the user

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
