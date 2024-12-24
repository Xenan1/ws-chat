import {WebSocketServer} from 'ws';

const wss = new WebSocketServer({ port: 80 });
const clients = new Map();
wss.on('connection', function connection(ws) {

    ws.on('error', console.error);

    ws.on('message', function message(data) {
        const message = JSON.parse(data);

        function sendMessage(recipient, message) {
            if (clients.has(recipient)) {
                const recipientWs = clients.get(recipient)
                recipientWs.send(JSON.stringify(message))
            }
        }

        if (message.type === 'register') {

            if (!clients.has(message.user_id)) {
                clients.set(message.user_id, ws)
            }

        } else if (message.type === 'message') {

            const { sender, recipient, text, date } = message

            const messageData = {
                sender: sender,
                text: text,
                date: date,
            }

            sendMessage(recipient, messageData);

        } else if (message.type === 'notification') {
            const {recipient, text} = message

            sendMessage(recipient, {text: text})
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