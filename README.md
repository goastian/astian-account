# Authorization Server (Dev)

Centralized authorization server using OAuth-server through Laravel and Laravel Passport as an abstraction or bridge layer. It implements broadcasting using [Echo Server](https://gitlab.com/elyerr/echo-server) and [Echo Client](https://gitlab.com/elyerr/echo-client-js).

This server was developed to facilitate the creation of microservices applications that can easily connect to the main server as if they were a unified system. With this authentication and authorization server, you can develop microservices or monolithic applications in any programming language and database manager. This enables developers to build applications in the language they are most proficient in, allowing for the creation of more complex applications.

---

## License

This project is licensed under the GNU Affero General Public License v3.0. See the [LICENSE](./LICENSE) file for details.

---

## Resources

-   [Official Documentation](https://gitlab.com/elyerr/outh2-passport-server/-/wikis/home) 
-   [Echo Server](https://gitlab.com/elyerr/echo-server)
-   [Echo Client](https://gitlab.com/elyerr/echo-client-js)

---

## Contact

For direct contact, visit [Telegram](https://t.me/elyerr).


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
