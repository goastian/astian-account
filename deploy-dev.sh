docker pull elyerr/oauth2-passport-server:dev && \
docker compose -f docker-compose-dev.yml down && \
docker compose -f docker-compose-dev.yml up -d --build && \
docker image prune -f
