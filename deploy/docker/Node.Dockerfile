FROM node:12.22

WORKDIR /app

COPY ./websocket/ /app/

RUN npm i

CMD ["node", "server.js"]