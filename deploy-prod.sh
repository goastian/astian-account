docker pull elyerr/oauth2-passport-server:latest && \
docker compose down && \
docker compose up -d --build && \
docker image prune -f
