<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <div>
        <form id="login-form">
            <input id="login-field" placeholder="Username">
            <input id="password-field" type="password" placeholder="Password">
            <button id="submit-button">Log in</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const button = document.getElementById('submit-button')

        button.addEventListener('click', (event) => {
            event.preventDefault()

            const login = document.getElementById('login-field').value;
            const password = document.getElementById('password-field').value;

            axios.post('{{ route('login') }}', {
                login: login,
                password: password
            })
                .then(response => {
                    if (response.status === 200) {
                        localStorage.setItem('bearer', response.data.access_token)
                        window.location.href = '{{ route('web.chat') }}'
                    } else {
                        alert('Invalid credentials')
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        })
    </script>
</html>
