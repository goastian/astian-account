# Guía de Despliegue en Entorno de Producción

Esta guía explica cómo desplegar el OAuth2 Passport Server en un entorno de producción utilizando Docker, Docker Compose y Nginx.

## Requisitos Previos

-   [Docker](https://docs.docker.com/get-docker/)
-   [Docker Compose](https://docs.docker.com/compose/install/)
-   [Nginx](https://nginx.org/) (utilizado como proxy inverso)

## Resumen de Ramas

- **main**: _[Estable]_ Rama que refleja la última versión estable de la aplicación.
- **vx.x.x**: _[Versión]_ Etiquetas siguiendo el versionado semántico, representando lanzamientos estables.
- **dev**: _[Desarrollo]_ Rama que contiene los cambios más recientes para pruebas; no está destinada para uso en producción.

## Configuración del Despliegue

1. Clona el repositorio:

    ```sh
    git clone git@github.com:elyerr/oauth2-passport-server.git
    cd oauth2-passport-server
    ```

2. Prepara tu configuración de entorno:

    ```sh
    cp .env.example .env
    ```

    Actualiza el archivo `.env` para tu configuración de producción.

    ```env
    APP_ENV=production
    APP_KEY= # Déjalo vacío; el sistema lo generará automáticamente al iniciar.
    APP_DEBUG=false
    APP_URL=https://<tu-dominio.com>
    FRONTEND_URL="${APP_URL}"
    ASSET_URL="${APP_URL}"
    SCHEMA_HTTPS=https

    # Registros
    LOG_CHANNEL=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    # Base de Datos
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=oauth2
    DB_USERNAME=<establece-el-usuario>
    DB_PASSWORD=<contraseña-muy-segura>
    ```

## Despliegue de la Aplicación

### Entorno de Producción

Despliega tu aplicación en producción ejecutando:

```bash
./deploy-prod.sh
```

### Entorno de Desarrollo

Para propósitos de desarrollo o pruebas (usando la rama dev):

```bash
./deploy-dev.sh
```

## Configuración del Primer Usuario

Después del despliegue, crea el primer usuario con el siguiente comando:

### Producción:

```bash
docker exec -it oauth2-server-app-1 php artisan settings:create-user
```

### Desarrollo:

```bash
docker exec -it oauth2-server-dev-app-1 php artisan settings:create-user
```

## Actualización a una Nueva Versión

### Entorno de Producción

Actualiza tu aplicación en producción ejecutando:

```bash
git pull origin main && ./deploy-prod.sh
```

### Entorno de Desarrollo

Para propósitos de desarrollo o pruebas (usando la rama dev):

```bash
git pull origin dev && ./deploy-dev.sh
```

## Configuración del Proxy Nginx (Ejemplo Básico)

A continuación se muestra una configuración de ejemplo para utilizar Nginx como proxy inverso. Puedes emplear Let's Encrypt para obtener certificados SSL válidos:

```nginx
server {
     listen 80;
     server_name <tu-dominio.com>;

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
