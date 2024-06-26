const $loginBtn = document.getElementById('loginBtn'), 
    $username = document.getElementById('username'),
    $password = document.getElementById('password'),
    $errorModal = document.getElementById('errorModal'),
    $errorModalBody = document.getElementById('errorModalBody');

if ($loginBtn) {
    //obtener la uri del proyecto con javascript
    const urirecord = window.location.href.split('/public/')[0] + '/public/';
    $loginBtn.addEventListener('click', () => {
        //crear peticion post con fetch
        fetch(urirecord + '/login/signin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: $username.value,
                password: $password.value
            })
        })
        .then(async (response) => {
            try {
                const res = await response.json();
                if (!res.status) {
                    // Mostrar el modal con el mensaje de error
                    $errorModalBody.textContent = res.message;
                    $('#errorModal').modal('show');
                } else {
                    window.location.href = urirecord + '/home';
                }
            } catch (error) {
                console.log(error);
            }
        });
    });
}

console.log('hola mundo');
