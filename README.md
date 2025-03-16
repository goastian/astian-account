[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f68c8a9e1c474a009876bd8c7eb5a227)](https://app.codacy.com/gh/goastian/astian-account/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

# [Astian Account Server](https://astian.org) 

[Astian](https://astian.org) Account Server is a service to unify the entire authentication and authorization process for Astian services.

## License

This project is licensed under the GNU Affero General Public License v3.0. See the [LICENSE](./LICENSE) file for details.

 

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