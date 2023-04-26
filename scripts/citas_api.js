"use strict"
const select_trabajador = document.getElementById("select-trabajador")
const select_fecha = document.getElementById("select-fecha")
const select_horas = document.getElementById("select-hora")
const options = document.querySelectorAll("#select-hora option")
let lista = []

datos()
async function datos(){
    const respuesta = await fetch('../php/api_citas.php')
    const datos = await respuesta.json();
    lista = datos["datos"];
    // console.log(datos)
}

select_horas.addEventListener("click", ()=>{
    if(select_fecha.value != ''){
        let citas_trab = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value)
        console.log(citas_trab)
    }else{
        const option = createOption('', "Elige trabajador y fecha")
        select_horas.innerHTML=''
        select_horas.appendChild(option)
    }
})

function createOption(value, texto){
    const elemento = document.createElement("option")
    elemento.setAttribute("value", value)
    elemento.innerText=texto
    return elemento
}