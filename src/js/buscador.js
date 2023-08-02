document-addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    //hacemos un query de el id fecha
    const fechaInput = document.querySelector('#fecha');
    //añadimos un evento y pasamos ese evento
    fechaInput.addEventListener('input', function(e) {
        // sacamos su valor y lo pasamos a la variable
        const fechaSeleccionada = e.target.value;
        //dirigimos a dicha fecha
        window.location = `?fecha=${fechaSeleccionada}`;
    })
}