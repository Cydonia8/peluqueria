"use strict"

const hoy=(new Date());
hoy.setHours(0,0,0,0)
let aa=hoy.getTime()+(1000*24*60*60*90)
const fin=new Date(aa);
const horas_container = document.querySelector(".horas-container")
let lista = []
let horas=[];

datos()
async function datos(){
    const respuesta = await fetch('../php/api_citas.php')
    const datos = await respuesta.json();
    lista = datos["datos"];
    // console.log(datos)
}

// const calendario = document.querySelectorAll("#calendario td:not(:empty)")
const flechas = document.querySelectorAll("caption a")
const select_servicio = document.getElementById("select-servicio")
const select_trabajador = document.getElementById("select-empleado")
let fiestas=[];

let fechas_horas

async function horarios(){
    const res = await fetch(`../php/api_fechas_horas.php`)
    const datos = await res.json()
    fechas_horas=datos["datos"]

    let fecha_actual = new Date()
    fecha_actual.setHours(0,0,0,0)
    // console.log(fechas_horas["horario"])
    let dias_extra = fechas_horas["horario"].filter(fecha=>{
        let comparar = new Date(fecha.dia)
        comparar.setHours(0,0,0,0)
        return comparar > fecha_actual && fecha.m_apertura === null && fecha.m_cierre === null && fecha.t_apertura === null && fecha.t_cierre === null
    }).map(fecha=>fecha.dia)

    dias_extra.forEach(dia=>fiestas.push(dia))

    const inicio=hoy.getTime();
    fechas_horas.descanso.forEach(d=>{
        let cierra=[];
        let dia_semana=inicio;
        while(new Date(dia_semana)<=fin){
            if(new Date(dia_semana).getDay()==d.dia){
                cierra.push(new Date(dia_semana).toLocaleDateString('en-CA').replaceAll("/","-"))
                dia_semana+=(1000*24*60*60*7)
            }else{
                dia_semana+=(1000*24*60*60)
            }
        }
        cierra.push(new Date(dia_semana).toLocaleDateString('en-CA').replaceAll("/","-"))
        cierra.forEach(dia=>fiestas.push(dia))
    })


}

document.addEventListener('DOMContentLoaded',async () => {
    const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
    const datos = await respuesta.json()
    fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN")).map(f=>f.date);
    await horarios();
    // console.log(fiestas)
    const calendar = new VanillaCalendar('#calendar',{
        settings: {
            range: {
                min: hoy.toLocaleDateString('en-CA').replaceAll("/","-"),
                max: fin.toLocaleDateString('en-CA').replaceAll("/","-"),
                disabled: [], // disabled dates
                enabled: [], // disabled dates
            },
            selected: {
                holidays: fiestas,
            },
            visibility: {
                // hightlights weekends
                weekend: false,
                // highlights today
                today: true,
                // abbreviated names of months in the month selection
                monthShort: true,
                // show week numbers of the year
                weekNumbers: false,
                // show all days, including disabled ones.s
                disabled: false,
                // show the days of the past and next month.
                daysOutside: true,
               },
          },
          actions: {
            clickDay(event, dates){
                horarioDia(dates[0])
                console.log(lista)
            }
          }
    });
    calendar.settings.lang = 'es';
    calendar.init();

    // const dias=document.getElementById("calendar").querySelectorAll(".vanilla-calendar-days>div")
    // fechas_horas.descanso.forEach(d=>{   
    //     for(let i=d.dia-1;i<dias.length;i+=7){
    //         dias[i].children[0].classList.add("vanilla-calendar-day__btn_holiday")
    //     }
    // })
        
    // dias.forEach(dia=>{
    //     dia.children[0].classList.add("dia_calendario");
    //     dia.children[0].setAttribute("disabled",true)
    //     dia.children[0].removeAttribute("type")
    //     dia.addEventListener("click",()=>{
    //         console.log("a")
    //             fechas_horas.descanso.forEach(d=>{   
    //                 for(let i=d.dia-1;i<dias.length;i+=7){
    //                     dias[i].children[0].classList.add("vanilla-calendar-day__btn_holiday")
    //                 }
    //             })
    //     })
    // })
});

select_servicio.addEventListener("change", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    const info = datos["datos"]
    selectTrabajador(info)
})

function selectTrabajador(info){
    select_trabajador.innerHTML=''
    if(info["realiza"].length > 1){
        select_trabajador.innerHTML='<option checked hidden>Elige un trabajador</option>'
    }
    select_trabajador.removeAttribute("disabled")
    info["realiza"].forEach(trab=>{
        let opt = document.createElement("option")
        opt.setAttribute("value", trab.empleado)
        let nombre = info["empleados"].filter(pers=>pers.id==trab.empleado).map(pers=>pers.nombre)
        opt.innerText=nombre
        select_trabajador.appendChild(opt)
    })
}

function horarioDia(date){
    let ocupadas=[];
    if(select_trabajador.value != 'Elige un trabajador'){
        console.log("entro")
        
        let filtrado = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === date)
        console.log(filtrado)
        filtrado.forEach(opcion=>{
            console.log("2")
            if(opcion.id_trab===select_trabajador.value && opcion.fecha === date && opcion.hora !== pillada){
                console.log(date)
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
                if(ocupado_h<10){
                    ocupado_h="0"+ocupado_h;
                }
                let fin=ocupado_h+":"+ocupado_min;
    
                console.log(inicio);
                console.log(fin);

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
                    i++
                    console.log(i)
                }
            }
        })
        
        console.log(horas)
        let disponibles=horas.filter(item => !ocupadas.includes(item));
        console.log(disponibles)
        disponibles.forEach(h=>{
            const option = createHour(h, h);
            horas_container.appendChild(option);
        })
    }else{
        const option = createOption('', "Elige trabajador y fecha")
        select_horas.innerHTML=''
        option.setAttribute("selected",'');
        option.setAttribute("hidden",'');
        option.setAttribute("disabled",'');
        select_horas.appendChild(option)
    }
}



// select_horas.addEventListener("click", ()=>{
    
//     console.log(select_horas)
//     let ocupadas=[];
//     if(select_fecha.value != ''){
//         let filtrado = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === select_fecha.value)
//         filtrado.forEach(opcion=>{
//             if(opcion.id_trab===select_trabajador.value && opcion.fecha === select_fecha.value && opcion.hora !== pillada){
//                 let dur=opcion.duracion.split(":");
//                 let time=opcion.hora.split(":");
//                 let ocupado_min=parseInt(dur[1])+parseInt(time[1]);
//                 let ocupado_h=parseInt(dur[0])+parseInt(time[0]);
//                 if(ocupado_min>=60){
//                     ocupado_h=parseInt(ocupado_h)+parseInt(ocupado_min)/60;
//                     ocupado_min=parseInt(ocupado_min)%60;
//                 }
//                 if(ocupado_min==0){
//                     ocupado_min="00";
//                 }
//                 let inicio=opcion.hora.split(":")[0]+":"+opcion.hora.split(":")[1];
//                 if(ocupado_h<10){
//                     ocupado_h="0"+ocupado_h;
//                 }
//                 let fin=ocupado_h+":"+ocupado_min;
    
//                 console.log(inicio);
//                 console.log(fin);

//                 while(inicio!=fin){
//                     ocupadas.push(inicio);
//                     let min2=inicio.split(":");
//                     min2[1]=parseInt(min2[1])+15;
//                     if(min2[1]>=60){
//                         min2[1]-=60;
//                         if(min2[1]==0){
//                             min2[1]="00";
//                         }
//                         min2[0]++;
//                     }
//                     inicio=min2[0]+":"+min2[1];
//                     i++
//                     console.log(i)
//                 }
//             }
//         })
        

//         let disponibles=horas.filter(item => !ocupadas.includes(item));
//         disponibles.forEach(h=>{
//             const option = createOption(h, h);
//             select_horas.appendChild(option);
//         })
//     }else{
//         const option = createOption('', "Elige trabajador y fecha")
//         select_horas.innerHTML=''
//         option.setAttribute("selected",'');
//         option.setAttribute("hidden",'');
//         option.setAttribute("disabled",'');
//         select_horas.appendChild(option)
//     }
// })

function createHour(value, texto){
    const elemento = document.createElement("button")
    elemento.setAttribute("value", value)
    elemento.innerText=texto
    return elemento
}