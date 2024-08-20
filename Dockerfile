FROM node:20-alpine as base
WORKDIR /app
COPY src/web ./
RUN npm i
RUN npm run build

FROM nginx as app
WORKDIR /usr/share/nginx/html
COPY --from=base /app/dist ./
EXPOSE 80
