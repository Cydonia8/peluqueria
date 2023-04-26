"use strict"
const select_trabajador = document.getElementById("select-trabajador")
const select_fecha = document.getElementById("select-fecha")
const select_horas = document.getElementById("select-hora")
const options = document.querySelectorAll("#select-hora option")
console.log(options)
let lista = []

datos();
console.log(lista);
async function datos(){
    const respuesta = await fetch('../php/api_citas.php')
    const datos = await respuesta.json();
    lista = datos["datos"];
    // console.log(datos)
}
updateSelects()
async function updateSelects(){
    await datos()
    // let trab = []
    // let fecha = []
    // console.log(lista.hora + lista.duracion)
    // select_trabajador.addEventListener("change", ()=>{
    //     trab.push(lista.filter(cit=>cit.id_trab===select_trabajador.value))
    // })
    // select_fecha.addEventListener("change", ()=>{
    //     fecha.push(lista.filter(cit=>cit.fecha===select_fecha.value))
    //     console.log(fecha)
    // })
    select_horas.addEventListener("click", ()=>{
        let filtrado=lista.filter(cit=>cit.fecha===select_fecha.value);
        if(select_trabajador.value!='Elige con quien quieres la cita'){
            filtrado=filtrado.filter(cit=>cit.id_trab===select_trabajador.value);
        }
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
        console.log(filtrado);
        // if(fecha.length > 0 && trab.length > 0){
        //     options.forEach(option=>{
                
        //     })
        // }
    })

    
}