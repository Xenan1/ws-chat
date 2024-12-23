<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chat</title>
    </head>
    <div class="chat">
        <div class="chat__header chat-header">
            <b class="chat-header__title"></b>
            <img class="chat-header__avatar">
        </div>
        <div class="chat__content chat-content">
            <div id="users-sidebar"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const user = axios.get('{{ route('me') }}')

        if (user.status !== 200) {
            window.location.href = '{{ route('web.login') }}'
        }

        const chats = await axios.get('{{ route('chats') }}', null, {
            headers: { Authorization: 'Bearer ' . localStorage.getItem('bearer')}
        })
            .then(response => {

                function createChatElement(chat)

                const chats = document.getElementById('users-sidebar')
                response.data.chats.forEach(item => {

                })
            })
    </script>
</html>
