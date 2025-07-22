# Desarrolladores

## Guía de Despliegue
Esta guía proporciona instrucciones completas y detalladas para desplegar el servicio en su entorno de desarrollo local.

### Ramas

- **main**: Rama estable con la última versión.
- **dev**: Rama de desarrollo con los últimos cambios.

### Requisitos Previos

Asegúrese de tener instaladas las siguientes dependencias:

- **nginx**
- **Composer**
- **Node.js** >= 22.17.1
- **Git**
- **PHP** >= 8.3.0

Extensiones PHP requeridas:

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

### Despliegue Local

1. Clone el repositorio:
    ```sh
    git clone git@github.com:elyerr/oauth2-passport-server.git
    cd oauth2-passport-server
    ```

### Configuración del Entorno

1. Copie el archivo de ejemplo del entorno:
    ```sh
    cp .env.example .env
    ```
2. Configure su archivo `.env`. Ejemplo (usando HTTPS con certificados autofirmados):

    ```env
    APP_ENV=dev
    APP_KEY= # Generado en el primer arranque
    APP_DEBUG=true
    APP_URL=https://auth.elyerr.xyz  # o use su IP y dirección
    FRONTEND_URL="${APP_URL}"
    ASSET_URL="${APP_URL}"
    SCHEMA_HTTPS=https # https o http

    # LOGS
    LOG_CHANNEL=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    # BASE DE DATOS
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=oauth2
    DB_USERNAME=admin
    DB_PASSWORD=admin
    ```

> **Consejo:** Genere certificados autofirmados usando [CA Generator](https://github.com/elyerr/ca-generator).


2. Instale las dependencias:
    ```sh
    composer install
    ```
    > Si encuentra errores, elimine `composer.lock` y vuelva a intentarlo. Asegúrese de tener instaladas todas las extensiones de PHP.
3. Inicialice la configuración del sistema:
    ```sh
    php artisan settings:system-start
    ```
4. Cree el primer usuario:
    ```sh
    php artisan settings:create-user
    ```

### Configuración

Inicie sesión con el usuario administrador y navegue a **Admin → Settings** para configurar:

- General
- Sesión (configuración de cookies)
- Pago
- Correo electrónico (despacho de eventos)
- Rutas (habilitar/deshabilitar rutas del sistema)
- Redis
- Caché
- Cola
- Sistema de Archivos
- Seguridad (protección en producción)
- Visor de Logs

### Ejecución de Procesos en Segundo Plano

**Comandos personalizados de Artisan:**

- Configuraciones:
    - `settings:channels-upload` — Subir canales predeterminados (Servidor de Broadcasting próximamente)
    - `settings:countries-upload` — Subir lista de países
    - `settings:create-user` — Crear un usuario con un rol seleccionado
    - `settings:key-generator` — Generar `APP_KEY` para `.env`
    - `settings:roles-upload` — Subir roles
    - `settings:system-start` — Instalar datos esenciales

- Pagos:
    - `payment:charge-recurring` — Habilitar pagos recurrentes automáticos

> Configure los ajustes de pago en **Admin → Settings → Payment**.

**Inicie el trabajador de colas para eventos, correos electrónicos y notificaciones:**
```sh
php artisan queue:work --tries=3
```

**(Opcional) Habilitar Pagos Recurrentes:**

Antes de iniciar los pagos recurrentes, asegúrese de que el webhook de Stripe esté en funcionamiento en su servidor local. Para configurarlo:

1. Inicie el listener del webhook de Stripe (reemplace `<your-machine-ip>` y `<your-system-port>` por sus valores reales):
    ```sh
    stripe listen --forward-to <your-machine-ip>:<your-system-port>/webhook/stripe
    ```
    > En el primer arranque, Stripe proporcionará una clave secreta para la firma del webhook. Agregue esta clave en **Admin → Settings → Payment → Stripe**.

2. En una nueva terminal, inicie el proceso de pago recurrente:
    ```sh
    php artisan payment:charge-recurring
    ```

**Desarrollo del Frontend:**

Ejecute lo siguiente solo si trabaja con activos de JavaScript:
```sh
npm run watch
```

### Configuración de Proxy en Nginx

Si utiliza un dominio personalizado localmente, agregue lo siguiente a su configuración de Nginx:

```nginx
server {
    listen 80;
    server_name auth.elyerr.xyz;

    # Para certificados autofirmados, descomente y configure las rutas correctas
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
