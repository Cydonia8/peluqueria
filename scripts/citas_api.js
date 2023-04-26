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
    
select_horas.addEventListener("click", ()=>{
    console.log(select_fecha.value);
    if(select_fecha.value != ''){
        let filtrado = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value)
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
            console.log(fin)
            
            
            // select_horas.children.forEach(child=>{
            //     if(!(child.innerText>=opcion.duracion && child.innerText<=fin)){
            //         const option1 = createOption()
            //     }
            // })
        })
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