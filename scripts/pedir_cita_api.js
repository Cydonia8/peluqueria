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

select_trabajador.addEventListener("click", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    console.log(datos)
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