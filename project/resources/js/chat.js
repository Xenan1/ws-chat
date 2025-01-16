import './app.js';

import routes from './routes/api.js';
import pages from './routes/pages.js';
import axios from "./axios.js";
import $ from 'jquery';

function isChatOpen() {
    return window.location.hash !== "";
}

function toggleChatInput() {
    const chatInput = $('#chat-input');
    if (isChatOpen()) {
        chatInput.show();
    } else {
        chatInput.hide();
    }
}

async function fillChatList() {
    try {
        const response = await axios.get(routes.chats);
        const chats = $('#users-sidebar');
        response.data.chats.forEach(item => {
            const chatItem = $('<div>', {
                class: 'chat-item',
                style: 'border: 1px solid #0d1116; padding: 5px',
                id: item.id,
                html: `<a href="${pages.chat}#${item.id}">${item.name}</a>`
            });
            chats.append(chatItem);
        });
    } catch (error) {
        console.error("Error fetching chat list", error);
    }
}

function getChatPartnerId() {
    return window.location.hash.slice(1);
}

function getMessagesBlock() {
    return $('#chat-messages');
}

function appendMessage(name, text, datetime, imageUrl, messagesBlock) {
    let messageHtml = `<b>${name}</b><p style="margin: 3px">${text}</p><span style="font-size: 9px; opacity: 0.5">${datetime}</span>`;

    if (imageUrl) {
        messageHtml = messageHtml + `<img alt="" src="${imageUrl}" width="100px" height="100px">`
    }

    const messageItem = $('<div>', {
        class: 'message',
        style: 'display: flex; flex-direction: column; margin: 7px',
        html: messageHtml
    });

    messagesBlock.append(messageItem)
}

async function fillDialog() {
    async function fetchDialogWithUser(userId) {
        try {
            const response = await axios.get(routes.dialog, {
                params: {chat_partner_id: userId}
            });

            if (response.status !== 200) {
                alert('Такого пользователя не существует');
                window.location.href = pages.chat;
                return;
            }

            return response.data;
        } catch (error) {
            console.error("Error fetching dialog", error);
        }
    }

    function fillDialogTitle(title) {
        $('#chat-title').html(title);
    }

    function fillMessages(messages) {
        const messagesBlock = getMessagesBlock();
        messagesBlock.empty();
        messages.forEach(message => {
            appendMessage(message.name, message.text, message.datetime, message.image, messagesBlock);
        });
    }

    const userId = Number(getChatPartnerId());
    if (Number.isInteger(userId)) {
        const dialogData = await fetchDialogWithUser(userId);
        if (dialogData) {
            fillDialogTitle(dialogData.name);
            fillMessages(dialogData.messages);
        }
    }
}

async function getUser() {
    try {
        const response = await axios.get(routes.user);
        return response.data;
    } catch (error) {
        if (error.status === 401) {
            window.location.href = pages.login;
        }
    }
}

function connectWebsocket(user) {
    const socket = new WebSocket('ws://localhost:4554');

    socket.onopen = () => {
        socket.send(JSON.stringify({
            type: 'register',
            user_id: user.id
        }));
    };

    socket.onmessage = (event) => {
        const message = JSON.parse(event.data);
        console.log(message)
        appendMessage(message.sender, message.text, message.date, message.image, getMessagesBlock());
    };

    return socket;
}

function messageHandling(user) {
    async function sendMessage(text, sender, image) {
        try {
            await axios.post(routes.newMessage, {
                message: text,
                recipient_id: getChatPartnerId(),
                sender_id: sender.id,
                image: image
            }, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
        } catch (error) {
            console.log(error)
            alert(error.data);
        }
    }

    $('#send-button').on('click', () => {
        const messageInput = $('#message-input');
        const messageText = messageInput.val();
        messageInput.val('');
        const imageFromInput = getImageFromInput();
        clearImageInput();

        if (messageText) {
            sendMessage(messageText, user, imageFromInput);
            appendMessage(user.name, messageText, getCurrentDate(), URL.createObjectURL(imageFromInput), getMessagesBlock());
        }
    });
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

function getImageFromInput() {
    const file = $('#image-input')[0].files[0]

    if (!file) {
        alert('Пожалуйста, выберите изображение.')
        return
    }

    return file
}

function clearImageInput() {
    $('#image-input').val('')
    $('#image-preview-container').hide()
}


function imageUploadInput() {
    const imageInput = $('#image-input');
    const imagePreview = $('#image-preview');
    const imagePreviewContainer = $('#image-preview-container');

    imageInput.on('change', function () {
        const file = this.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.attr('src', e.target.result);
                imagePreview.show();
                imagePreviewContainer.show();
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.attr('src', '');
            imagePreview.hide();
            imagePreviewContainer.hide();
            alert('Пожалуйста, выберите изображение.');
        }
    });
}

$(document).ready(async () => {
    const user = await getUser();
    connectWebsocket(user);

    await fillChatList();
    await fillDialog();

    $(window).on('hashchange', () => {
        toggleChatInput();
        fillDialog();
    });

    messageHandling(user);

    imageUploadInput();
    toggleChatInput();

});
