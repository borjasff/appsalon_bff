//variable de apartados y el número del apartado inicial y final
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

//objeto de los datos de cita
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

//cargamos los eventos cuando el documento esta cargado, en este caso inicia una funcion
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion();//muestra y oculta las secciones
    tabs();//cambia la dirección cuando se presione un tab(ejemplo:resumen)
    botonesPaginador();//agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();//Consulta la api en el backend de php

    idCliente();//recuperar el id
    nombreCliente();//recuperar nombre cliente
    seleccionarFecha(); //añade la fecha en el objeto
    seleccionarHora(); //añade la hora en el objeto

    mostrarResumen();//mostramos el resumen de las citas
}
function mostrarSeccion(){

    //ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
    seccionAnterior.classList.remove('mostrar');    
    }

    //seleccionar la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //eliminamos el resaltado previamente con tab
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
    tabAnterior.classList.remove('actual');    
    }

    //Resaltar el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}
function tabs(){
    //seleccionamos todas las coincidencias
    const botones = document.querySelectorAll('.tabs button');
    //al pulsar el boton ocurre un evento
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            //guardamos la informacion convertida en int en paso
            paso = parseInt(e.target.dataset.paso);
            //llamamos
            mostrarSeccion();
            botonesPaginador();//agrega o quita los botones del paginador

    })
    });
}

function botonesPaginador(){
    //obtenes la informacion de los botones
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if (paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();//refrescar y validar los objetos en el apartado resumen
    } else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    //llamamos a mostrar sección para unir el cambio de boton con lo mostrado de contenido
    mostrarSeccion();
}
function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso<= pasoInicial) return;
            paso--;
            //para que se hagan los cambios del la app
            botonesPaginador();
        }
    )
}
function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
        paso++;
        //para que se hagan los cambios del la app
        botonesPaginador();
    })
}

async function consultarAPI(){

    try {
        const url = '/api/servicios';
        //await para funciones asincronas que hace esperar a la solicitud del codigo por el fetch
        const resultado = await fetch(url);
        const servicios = await resultado.json();

        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }

}

function mostrarServicios(servicios){
    //para mostrar los datos por pantalla de los servicios
        //iteramos cada parte
    servicios.forEach(servicio => {
        //declaramos el nombre, creamos su parrafo, su clase y contenido
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        //declaramos el precio, creamos su parrafo, su clase y contenido
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        //declaramos el contenedor, creamos su div, su clase y contenido que obtiene
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function(){
            //para incluir en el arreglo cita al pulsar el div se incluye
            seleccionarServicio(servicio);
        } 

        //para mostrar por pantalla creamos el div con el nombre y precio
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
        
    })
}
function seleccionarServicio(servicio){
    //para dar otro formato de color al seleccionado
    const {id} = servicio;
    //agregamos los servicios a cita
    const {servicios} = cita;
    //Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //compreobar si un servicio ya fue agregado sobre los servicios con un true o false
    if(servicios.some(agregado => agregado.id === id)){
        //eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else{
        //agregarlo
        //a través de extraer los datos de servicios y hacer una copia en servicios de cita
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }




    console.log(cita);
}

function idCliente(){
    //añadimos el id al objeto
    const id = document.querySelector('#id').value;
    cita.id = id;
}

function nombreCliente(){
    //añadimos el nombre al objeto
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
}

function seleccionarFecha(){
    //añadimos la fecha al objeto
    const inputFecha = document.querySelector('#fecha');
    //agregamos el evento a través de target q dispara la funcion
    inputFecha.addEventListener('input', function(e){
        //verificamos el día
        const dia = new Date(e.target.value).getUTCDay();

        if( [6, 0].includes(dia)){
            //si es sabado o domingo no se selecciona
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        } else{
            //guardamos fecha
            cita.fecha = e.target.value;
        }
    });

}

function seleccionarHora(){
    //añadimos la hora al objeto
    const inputHora = document.querySelector('#hora');
    //agregamos el evento a través de target q dispara la funcion
    inputHora.addEventListener('input', function(e){
        //verificamos la hora
        const horaCita = e.target.value;
        //separamos y extraemos la hora
        const hora = horaCita.split(":")[0];


        if( hora < 10 || hora > 18){
            //si es x hora no se podrá
            e.target.value = '';
            mostrarAlerta('Hora no Válida', 'error', '.formulario');
        } else{
            //guardamos hora
            cita.hora = e.target.value;
        }
    });

}
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){

    //para solo mostrar una alerta de fecha a la vez
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    //mostrar error de mensaje en fechas
    const alerta = document.createElement('DIV');

    //agregamos el mensaje
    alerta.textContent = mensaje;

    //agregamos las clases
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //mostramos por pantalla
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    //desaparece
    if(desaparece){
        //eliminar la alerta
        setTimeout(()=>{
            alerta.remove();
        },3000);
    }


}

function mostrarResumen(){
    //obtenes el paso 3 resumen
    const resumen = document.querySelector('.contenido-resumen');

    //limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    //si incluye algun dato vacio damos un error
    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Hacen falta datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);
        return;
    }

    //Formatear el div de resumen
    const {nombre, fecha, hora, servicios} = cita;

    //heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';

    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        //declaramos las partes del objeto
        const {id, precio, nombre} = servicio;

        //creamos div para dar estilos
        const contenedorServicio =document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        //creamos un parrafo para servicios
        const textoServicio =document.createElement('P');
        //creamos un span de html
        textoServicio.textContent = nombre;

        //para mostrar el precio
        const precioServicio =document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span> $${precio}`;

        //contenedor
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })

    //heading para cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';

    resumen.appendChild(headingCita);

    //creamos un parrafo para el nombre
    const nombreCliente =document.createElement('P');
    //creamos un span de html
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    //Date desfasa un día y como lo usamos dos veces usamos un + 2 para recuperarlo
    const year = fechaObj.getFullYear();

    //pasamos opciones de como queremos ver los datos
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    //instanciamos la fecha
    const fechaUTC =new Date(Date.UTC(year, mes, dia));
    //regresa la fecha formateada en un idioma específico y sus opciones si las queremos
    const fechaFormateada =fechaUTC.toLocaleDateString('es-ES', opciones);
    //no modificamos el objeto original solo damos formato 


    //creamos un parrafo para fecha
    const fechaCita =document.createElement('P');
    //creamos un span de html
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    //creamos un parrafo para hora
    const horaCita =document.createElement('P');
    //creamos un span de html
    horaCita.innerHTML = `<span>Hora: </span> ${hora} Horas`;

    //boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    //añadimos por pantalla
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

//async ya que necesitamos que espere al servidor
async function reservarCita(){
    //fetch API
    //declaramos variable e incluimos valores, funciona como submit pero en la API
    const {nombre, fecha, hora, servicios, id } = cita;

    //necesitamos los id, foreach solo itera y map las coincidencias las incluye en la variable
    const idServicios = servicios.map( servicio => servicio.id);
    console.log(idServicios);


    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);
    
    //si ha ocurrido un error vamos a crear una estructura de errores
    try {
        //PETICION HACIA LA API
    const url = '/api/citas';

    //UTILIZAMOS UN METODO POST EN ESTA URL
    const respuesta = await fetch(url, {
        method: 'POST', 
        body: datos
    });

    //metodo que podemos usar del prototipe
    const resultado = await respuesta.json();

    console.log(resultado.resultado);
    //Creamos la extensión que nos lleva a un cuadro de confirmación de la reserva y recargamos la pagina posteriormente con un callback
    if(resultado.resultado){
        Swal.fire({
            icon: 'success',
            title: 'Cita Creada',
            text: 'Cita creada correctamente',
            button: 'OK'
          }).then( () => {
            setTimeout(() => {
                window.location.reload();
            }, 3000);
          })
    }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Algo ha salido mal'
          })
    }
    


    //para comprobar que enviamos
    //console.log([...datos]);
}

