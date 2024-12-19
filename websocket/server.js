import {WebSocketServer} from 'ws';

const wss = new WebSocketServer({ port: 80 });
const clients = new Map();
wss.on('connection', function connection(ws) {

    ws.on('error', console.error);

    ws.on('message', function message(data) {
        const message = JSON.parse(data);

        if (message.type === 'register') {

            clients.set(message.user_id, ws)

        } else if (message.type === 'message') {

            const { sender, recipient, text, date } = message

            if (clients.has(recipient)) {
                const recipientWs = clients.get(recipient)
                recipientWs.send(JSON.stringify({
                    sender: sender,
                    text: text,
                    date: date,
                }))
            }
        }
    });

    ws.on('close', () => {
        for (const [userId, client] of clients.entries()) {
            if (client === ws) {
                clients.delete(userId);
            }
        }
    })
})