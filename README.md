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
