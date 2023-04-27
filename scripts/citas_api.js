"use strict"
const select_trabajador = document.getElementById("select-trabajador")
const select_fecha = document.getElementById("select-fecha")
const select_horas = document.getElementById("select-hora")
let lista = []

datos()
async function datos(){
    const respuesta = await fetch('../php/api_citas.php')
    const datos = await respuesta.json();
    lista = datos["datos"];
    // console.log(datos)
}

let horario=[];
let horas=[];
horario.push(select_horas.getAttribute("data-mi"));
horario.push(select_horas.getAttribute("data-mf"));
horario.push(select_horas.getAttribute("data-ti"));
horario.push(select_horas.getAttribute("data-tf"));
let i=0;

while(i<horario.length){
    let tiempo=horario[i];
    tiempo=tiempo.split(":")[0]+":"+tiempo.split(":")[1];
    i++;
    let limite=horario[i].split(":")[0]+":"+horario[i].split(":")[1];
    while(tiempo<limite){
        horas.push(tiempo);
        let min=tiempo.split(":");
        min[1]=parseInt(min[1])+15;
        if(min[1]>=60){
            min[1]-=60;
            if(min[1]==0){
                min[1]="00";
            }
            min[0]++;
        }
        tiempo=min[0]+":"+min[1];
    }
    i++;
}
console.log(horas)
console.log(horario)
    
select_horas.addEventListener("click", ()=>{
    let ocupadas=[];
    if(select_fecha.value != ''){
        let filtrado = lista.filter(cita=>(cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value && cita.hora ==select_horas.value+":00") || (cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value))
        filtrado.forEach(opcion=>{
            let dur=opcion.duracion.split(":");
            let time=opcion.hora.split(":");
            let ocupado_min=parseInt(dur[1])+parseInt(time[1]);
            let ocupado_h=parseInt(dur[0])+parseInt(time[0]);
            if(ocupado_min>=60){
                ocupado_h=parseInt(ocupado_h)+parseInt(ocupado_min)/60;
                ocupado_min=parseInt(ocupado_min)%60;
            }
            if(ocupado_min==0){
                ocupado_min="00";
            }
            let inicio=opcion.hora.split(":")[0]+":"+opcion.hora.split(":")[1];
            let fin=ocupado_h+":"+ocupado_min;

            while(inicio!=fin){
                ocupadas.push(inicio);
                let min2=inicio.split(":");
                min2[1]=parseInt(min2[1])+15;
                if(min2[1]>=60){
                    min2[1]-=60;
                    if(min2[1]==0){
                        min2[1]="00";
                    }
                    min2[0]++;
                }
                inicio=min2[0]+":"+min2[1];
            }
        })

        let disponibles=horas.filter(item => !ocupadas.includes(item));
        select_horas.innerHTML='';
        disponibles.forEach(h=>{
            const option = createOption(h, h);
            select_horas.appendChild(option);
        })
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