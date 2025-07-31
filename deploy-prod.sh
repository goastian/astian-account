docker pull elyerr/oauth2-passport-server:v3.0.2 && \
docker compose down && \
docker compose up -d --build && \
docker image prune -f
