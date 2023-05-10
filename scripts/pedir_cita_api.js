"use strict"

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
    console.log(fechas_horas["horario"])
    let dias_extra = fechas_horas["horario"].filter(fecha=>{
        let comparar = new Date(fecha.dia)
        comparar.setHours(0,0,0,0)
        return comparar > fecha_actual && fecha.m_apertura === null && fecha.m_cierre === null && fecha.t_apertura === null && fecha.t_cierre === null
    }).map(fecha=>fecha.dia)

    dias_extra.forEach(dia=>fiestas.push(dia))
}

document.addEventListener('DOMContentLoaded',async () => {
    const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
    const datos = await respuesta.json()
    fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN")).map(f=>f.date);
    await horarios();
    console.log(fiestas)
    const calendar = new VanillaCalendar('#calendar',{
        settings: {
            range: {
                disabled: [], // disabled dates
                enabled: [], // disabled dates
            },
            selected: {
                holidays: fiestas,
            },
          },
    });
    calendar.settings.lang = 'es';
    calendar.init();

    const dias=document.getElementById("calendar").querySelectorAll(".vanilla-calendar-days>div")
    fechas_horas.descanso.forEach(d=>{   
        for(let i=d.dia-1;i<dias.length;i+=7){
            dias[i].children[0].classList.add("vanilla-calendar-day__btn_holiday")
        }
    })
        
    dias.forEach(dia=>{
        dia.children[0].setAttribute("disabled",true)
    })
});

select_servicio.addEventListener("change", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    const info = datos["datos"]
    selectTrabajador(info)
})

function selectTrabajador(info, servicio){
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

// let fiestas;

// document.addEventListener('DOMContentLoaded',async () => {
//     const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
//     const datos = await respuesta.json()
//     fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN")).map(f=>f.date);
 
//     const calendar = new VanillaCalendar('#calendar',{
//         settings: {
//             range: {
//                 disabled: [], // disabled dates
//                 enabled: [], // disabled dates
//             },
//             selected: {
//                 holidays: fiestas,
//             },
//         },
//     });
//     calendar.settings.lang = 'es';
//     calendar.init();
    
//     const dias=document.getElementById("calendar").querySelectorAll(".vanilla-calendar-days>div")
//     fechas_horas.descanso.forEach(d=>{   
//         for(let i=d.dia-1;i<dias.length;i+=7){
//             dias[i].children[0].classList.add("vanilla-calendar-day__btn_holiday")
//         }
//     })
        
//     dias.forEach(dia=>{
//         dia.children[0].setAttribute("disabled",true)
//     })
        
// });