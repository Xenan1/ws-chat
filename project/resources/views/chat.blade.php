<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chat</title>
</head>
<body>
<div class="chat">
    <div class="chat__content chat-content" style="display: flex; flex-direction: row; height: 100vh;">
        <div id="users-sidebar" style="border: 3px solid #0d1116; flex: 0 0 20%;"></div>
        <div id="current-chat" style="flex-grow: 1; display: flex; flex-direction: column;">
            <div class="chat-header" style="height: 5vh; margin: 0; padding: 0;">
                <b id="chat-title"></b>
            </div>
            <div id="chat-messages"
                 style="flex-grow: 1; display: flex; flex-direction: column; margin: 0; padding: 0 0 60px;"></div>
            <div class="chat-input" id="chat-input"
                 style="position: fixed; bottom: 0; left: 0; width: 100%; padding: 10px; background-color: #fff; border-top: 2px solid #ccc; display: flex; z-index: 100; display: none;">
                <input type="text" id="message-input" placeholder="Введите сообщение..."
                       style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <input type="file" id="image-input" accept="image/*"
                       style="margin-left: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;">
                <button id="send-button"
                        style="margin-left: 10px; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px;">
                    Отправить
                </button>
                <div id="image-preview-container" style="display: none; padding: 10px; border-top: 1px solid #ccc; background-color: #f9f9f9;">
                    <p style="margin: 0; font-size: 14px; color: #666;">Предпросмотр изображения:</p>
                    <img id="image-preview" src="" alt="Предпросмотр" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 5px; display: none;">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="chat.blade.php"></script>
</body>
</html>
