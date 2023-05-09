"use strict"
const calendario = document.querySelectorAll("#calendario td:not(:empty)")
const flechas = document.querySelectorAll("caption a")
const select_servicio = document.getElementById("select-servicio")
const select_trabajador = document.getElementById("select-empleado")
let fiestas

festivos()
async function festivos(){
    const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
    const datos = await respuesta.json()
    fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN"))
    calendario.forEach(cal=>{
        let fecha = cal.getAttribute("data-date")
        fiestas.forEach(festivo=>{
            if(festivo.date == fecha){
                cal.classList.add("festivo")
            }
        })
    })
}
flechas.forEach(flecha=>{
    flecha.addEventListener("click", comprobarFestivos)
})

select_servicio.addEventListener("change", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    const info = datos["datos"]
     selectTrabajador(info)
})

function comprobarFestivos(){
    calendario.forEach(cal=>{
        let fecha = cal.getAttribute("data-date")
        fiestas.forEach(festivo=>{
            if(festivo.date == fecha){
                cal.classList.add("festivo")
            }
        })
    })
}

 function selectTrabajador(info){
    select_trabajador.innerHTML=''
    info["realiza"].forEach(trab=>{
        let opt = document.createElement("option")
        opt.setAttribute("value", trab.empleado)
        let nombre = info["empleados"].filter(pers=>pers.id==trab.empleado).map(pers=>pers.nombre)
        opt.innerText=nombre
        select_trabajador.appendChild(opt)
    })
}
document.addEventListener('DOMContentLoaded',async () => {
    const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
    const datos = await respuesta.json()
    let fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN")).map(f=>f.date);

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
  });