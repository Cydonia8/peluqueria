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
    console.log(select_fecha.value);
    if(select_fecha.value != ''){
        let citas_trab = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value)
        console.log(citas_trab)
    }else{
        const option = createOption('', "Elige trabajador y fecha")
        select_horas.innerHTML=''
        select_horas.appendChild(option)
        
        filtrado.forEach(opcion=>{
            let dur=opcion.duracion.split(":");
            let time=opcion.hora.split(":");
            let ocupado_min=dur[1]+time[1];
            let ocupado_h=dur[0]+time[0];
            if(ocupado_min>60){
                ocupado_h+=ocupado_min/60;
                ocupado_min%=60;
            }
            let fin=ocupado_h+":"+ocupado_min;
            select_horas.children.forEach(child=>{
                if(child.innerText>=opcion.duracion && child.innerText<=fin){
                    child.style.display="none";
                }
            })
        })
    }
})

function createOption(value, texto){
    const elemento = document.createElement("option")
    elemento.setAttribute("value", value)
    elemento.innerText=texto
    return elemento
}