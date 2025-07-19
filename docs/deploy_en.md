# Production Environment Deployment Guide

This guide explains how to deploy the OAuth2 Passport Server in a production environment using Docker, Docker Compose, and Nginx.

## Prerequisites

-   [Docker](https://docs.docker.com/get-docker/)
-   [Docker Compose](https://docs.docker.com/compose/install/)
-   [Nginx](https://nginx.org/) (used as a reverse proxy)

## Branches Overview

- **main**: _[Stable]_ Branch that reflects the latest stable release of the application.
- **vx.x.x**: _[Release]_ Version tags following semantic versioning, representing stable releases.
- **dev**: _[Development]_ Branch containing the most recent changes for testing purposes; not intended for production use.

## Deployment Setup

1. Clone the repository:

    ```sh
    git clone git@github.com:elyerr/oauth2-passport-server.git
    cd oauth2-passport-server
    ```

2. Prepare your environment configuration:

    ```sh
    cp .env.example .env
    ```

    Update the `.env` file for your production setup.

    ```env
    APP_ENV=production
    APP_KEY= # Leave empty; the system generates it automatically on startup.
    APP_DEBUG=false
    APP_URL=https://<your-domain.com>
    FRONTEND_URL="${APP_URL}"
    ASSET_URL="${APP_URL}"
    SCHEMA_HTTPS=https

    # Logs
    LOG_CHANNEL=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    # Database
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=oauth2
    DB_USERNAME=<set-user-name>
    DB_PASSWORD=<strongest-password>
    ```

## Deploying the Application

### Production Environment

Deploy your application in production by running:

```bash
./deploy-prod.sh
```

### Development Environment

For development or testing purposes (using the dev branch):

```bash
./deploy-dev.sh
```

## Initial User Setup

After deployment, create the first user with the following command:

### Production:

```bash
docker exec -it oauth2-server-app-1 php artisan settings:create-user
```

### Development:

```bash
docker exec -it oauth2-server-dev-app-1 php artisan settings:create-user
```

## Update to new version

### Production Environment

Update your application in production by running:

```bash
git pull origin main && ./deploy-prod.sh
```

### Development Environment

For development or testing purposes (using the dev branch):

```bash
git pull origin dev && ./deploy-dev.sh
```

## Nginx Proxy Configuration (Basic Example)

Below is a sample configuration for using Nginx as a reverse proxy. You can employ Let's Encrypt to obtain valid SSL certificates:

```nginx
server {
     listen 80;
     server_name <your-domain.com>;

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
