<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chat</title>
    </head>
    <div class="chat">
        <div class="chat__content chat-content" style="display: flex; flex-direction: row; height: 100vh;">
            <div id="users-sidebar" style="border: 3px solid #0d1116; flex: 0 0 20%;"></div>
            <div id="current-chat" style="flex-grow: 1; display: flex; flex-direction: column;">
                <div class="chat-header" style="height: 5vh; margin: 0; padding: 0;">
                    <b id="chat-title"></b>
                </div>
                <div id="chat-messages" style="flex-grow: 1; display: flex; flex-direction: column; margin: 0; padding: 0 0 60px;"></div>
                <div class="chat-input" id="chat-input" style="position: fixed; bottom: 0; left: 0; width: 100%; padding: 10px; background-color: #fff; border-top: 2px solid #ccc; display: flex; z-index: 100; display: none;">
                    <input type="text" id="message-input" placeholder="Введите сообщение..." style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <button id="send-button" style="margin-left: 10px; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px;">Отправить</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        function isChatOpen() {
            return window.location.hash !== "";
        }

        function toggleChatInput() {
            const chatInput = document.getElementById('chat-input');

            if (isChatOpen()) {
                chatInput.style.display = 'flex';
            } else {
                chatInput.style.display = 'none';
            }
        }

        async function fillChatList() {
            await axios.get('{{ route('chats') }}')
                .then(response => {

                    function createChatElement(chat) {
                        const chatItem = document.createElement('div')
                        chatItem.className = 'chat-item'
                        chatItem.setAttribute('style', 'border: 1px solid #0d1116; padding: 5px')
                        chatItem.id = chat.id
                        chatItem.innerHTML = `<a href="{{ route('web.chat') }}#${chat.id}">${chat.name}</a>`

                        return chatItem
                    }

                    const chats = document.getElementById('users-sidebar')
                    response.data.chats.forEach(item => {
                        const chatHtml = createChatElement(item)
                        chats.appendChild(chatHtml)
                    })
                })
        }

        function getChatPartnerId() {
            return window.location.hash.slice(1);
        }

        function getMessagesBlock() {
            return document.getElementById('chat-messages');
        }

        function appendMessage(name, text, datetime, messagesBlock) {
            const messageItem = document.createElement('div')
            messageItem.className = 'message'
            messageItem.setAttribute('style', 'display: flex; flex-direction: column; margin: 7px')
            messageItem.innerHTML = `<b>${name}</b><p style="margin: 3px">${text}</p><span style="font-size: 9px; opacity: 0.5">${datetime}</span>`
            messagesBlock.appendChild(messageItem)
        }

        async function fillDialog() {

            async function fetchDialogWithUser(userId) {

                const response = await axios.get('{{ route('dialog') }}', {
                    params: {
                        chat_partner_id: userId
                    }
                })

                if (response.status !== 200) {
                    alert('Такого пользователя не существует')
                    window.location.href = '{{ route('web.chat') }}'
                    return
                }

                return response.data
            }

            function fillDialogTitle(title) {
                const dialogTitle = document.getElementById('chat-title')
                dialogTitle.innerHTML = title
            }

            function fillMessages(messages) {
                const messagesBlock = getMessagesBlock();
                messagesBlock.innerHTML = ''
                messages.forEach(message => {
                    appendMessage(message.name, message.text, message.datetime, messagesBlock);
                })
            }

            const userId = Number(getChatPartnerId())
            if (Number.isInteger(userId)) {
                const dialogData =  await fetchDialogWithUser(userId)
                fillDialogTitle(dialogData.name)
                fillMessages(dialogData.messages)
            }
        }

        async function getUser() {

            try {
                const response = await axios.get('{{ route('me') }}')
                return response.data
            } catch (error) {
                if (error.status === 401) {
                    window.location.href = '{{ route('web.login') }}'
                }
            }


        }

        function connectWebsocket(user) {
            const socket = new WebSocket('ws://localhost:4554')

            socket.onopen = () => {

                socket.send(JSON.stringify({
                    type: 'register',
                    user_id: user.id
                }))
            }

            socket.onmessage = (event) => {
                const message = JSON.parse(event.data)
                appendMessage(message.sender, message.text, message.date, getMessagesBlock())
            }

            return socket
        }

        function messageHandling(user) {

            async function sendMessage(text, sender) {
                await axios.post('{{ route('newMessage') }}', {
                    message: text,
                    recipient_id: getChatPartnerId(),
                    sender_id: sender.id
                })
                    .catch(error => {
                        alert(error.data)
                    })
            }

            const messageButton = document.getElementById('send-button')

            messageButton.addEventListener('click', () => {
                const messageInput = document.getElementById('message-input');
                const messageText = messageInput.value;
                messageInput.value = ''

                if (messageText) {
                    sendMessage(messageText, user)
                    appendMessage(user.name, messageText, getCurrentDate(), getMessagesBlock())
                }
            })
        }

        function getCurrentDate() {
            const now = new Date();

            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const formatted = new Intl.DateTimeFormat('ru-RU', options).format(now);

            return formatted.replace(',', '').replace(/\./g, '-').replace(' ', 'T').split('T').join(' ');
        }

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bearer')

        let user = getUser()
            .then(user => {
                const ws = connectWebsocket(user)

                fillChatList()

                fillDialog()
                window.addEventListener('hashchange', () => {
                    toggleChatInput()
                    fillDialog()
                });

                toggleChatInput();

                messageHandling(user)
            });
    </script>
</html>
