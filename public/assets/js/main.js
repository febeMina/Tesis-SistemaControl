const $cerraSesion = document.getElementById('cerraSesion');

if($cerraSesion){
    $cerraSesion.addEventListener('click', function(e){
        console.log("Click en cerrar Sesion");
        //realizar una peticion con fetch mediante el metodo post
        const urirecord = window.location.href.split('/public/')[0] + '/public/';
        console.log(urirecord);

        fetch(urirecord + '/login/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
    .then(async (response) => {
        try {
            const res = await response.json();
            if(res.status){
                window.location.href = urirecord + '/login';
            }
        } catch (error) {
            console.log(error);
        }
    }).catch(function(error){
        console.log(error);
    })
    });
}else{
    console.log("no existe el boton de cerrar sesion");
}

console.log('hola mundo desde el home');