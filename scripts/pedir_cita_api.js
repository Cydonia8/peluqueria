"use strict"
// const calendario = document.querySelectorAll("#calendario td:not(:empty)")
const flechas = document.querySelectorAll("caption a")
const select_servicio = document.getElementById("select-servicio")
const select_trabajador = document.getElementById("select-empleado")
const contenedor_horas = document.getElementById("horas")
const cal=document.getElementById("calendar");
cal.classList.add("capa")
let fiestas=[];
let fechas_horas

const hoy=(new Date());
hoy.setHours(0,0,0,0)
let aa=hoy.getTime()+(1000*24*60*60*90)
const fin=new Date(aa);
const horas_container = document.querySelector(".horas-container")
let lista = []
let horas=[];

async function activar_calendario(){
    if(select_servicio.value!="Elige un servicio" && select_trabajador.value!="Elige un trabajador"){
        cal.classList.remove("capa")
    }else{
        cal.classList.add("capa")
    }
}

async function datos_hor(dia){
    let horario=[];
    horario.length=0
    const respuesta = await fetch('../php/api_citas.php')
    const datos = await respuesta.json();
    lista = datos["datos"];
    const respuesta2 = await fetch('../php/api_fechas_horas.php')
    const datos2 = await respuesta2.json();
    console.log(datos2["datos"].trabaja.find(t=>t.empleado==select_trabajador.value))
    if(datos2["datos"].trabaja.find(t=>t.empleado==select_trabajador.value)){
        const personalizado=datos2["datos"].trabaja.find(t=>t.empleado==select_trabajador.value)
        horario.push(personalizado.m_inicio);
        horario.push(personalizado.m_fin);
        horario.push(personalizado.t_inicio);
        horario.push(personalizado.t_fin);
        horario=horario.filter(item=>item!=undefined)
        console.log(horario)
    }else{
        horario.push(datos2["datos"].horario[0].m_apertura);
        horario.push(datos2["datos"].horario[0].m_cierre);
        horario.push(datos2["datos"].horario[0].t_apertura);
        horario.push(datos2["datos"].horario[0].t_cierre);
    }

    let horas=[];
    let contador=0;

    while(contador<horario.length){
        
        let tiempo=horario[contador];
        tiempo=tiempo.split(":")[0]+":"+tiempo.split(":")[1];
        contador++;
        let limite=horario[contador].split(":")[0]+":"+horario[contador].split(":")[1];
        while(tiempo<limite){
            console.log("2")
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
        contador++;
    }

    contenedor_horas.innerHTML="";
    let ocupadas=[];
    let inicio;
    const dura=datos2["datos"].servicios.find(s=>s.id==select_servicio.value).duracion.split(":")[0]+":"+datos2["datos"].servicios.find(s=>s.id==select_servicio.value).duracion.split(":")[1]
    const ciclos=(dura.split(":")[0]*60+dura.split(":")[1])/15-1;

    for(let i=1;i<horario.length;i+=2){
        inicio=horario[i];
        for(let i=0;i<ciclos;i++){
            let h=inicio.split(":")[0]
            let m=inicio.split(":")[1]
            if(m>0){
                m-=15;
                if(m==0){
                    m='00'
                }
            }else{
                h-=1;
                m=45;
            }
            inicio=h+":"+m;
        }

        while(inicio!=horario[i].split(":")[0]+":"+horario[i].split(":")[1]){
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
    }  

    if(contenedor_horas.value != ''){
        let filtrado = lista.filter(cita=>cita.id_trab===select_trabajador.value && cita.fecha === dia)
        filtrado.forEach(opcion=>{
            if(opcion.id_trab===select_trabajador.value && opcion.fecha === dia && opcion.hora){


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
                inicio=opcion.hora.split(":")[0]+":"+opcion.hora.split(":")[1];
                if(ocupado_h<10){
                    ocupado_h="0"+ocupado_h;
                }
                let fin=ocupado_h+":"+ocupado_min;

                for(let i=0;i<ciclos;i++){
                    let h=inicio.split(":")[0]
                    let m=inicio.split(":")[1]
                    if(m>0){
                        m-=15;
                        if(m==0){
                            m='00'
                        }
                    }else{
                        h-=1;
                        m=45;
                    }
                    inicio=h+":"+m;
                }
                
                while(inicio!=fin){
                    console.log(inicio)
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
            }
        })  

        let disponibles=horas.filter(item => !ocupadas.includes(item));
        contenedor_horas.innerHTML="";
        let n=0;
        disponibles.forEach(h=>{
            contenedor_horas.innerHTML+=`
                <input type='checkbox' class='btn-check' value=${h} id='btn-check-2-outlined-${n}' checked autocomplete='off'>
                <label class='btn btn-outline-secondary' for='btn-check-2-outlined-${n}'>${h}</label>
            `;
            n++;
        })
    }
}   

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
            async clickDay(event, dates){
                await datos_hor(dates[0])         
            }
        }
    });
    calendar.settings.lang = 'es';
    calendar.init();
});


select_servicio.addEventListener("change", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    const info = datos["datos"]
    selectTrabajador(info)
    activar_calendario()
})
select_trabajador.addEventListener("change", async ()=>{
    activar_calendario()
    let fecha_selec;
    const dia=cal.querySelector(".vanilla-calendar-day.vanilla-calendar-day_selected>button");
    if(dia!=null){
        fecha_selec=dia.getAttribute("data-calendar-day");
    }
    datos_hor(fecha_selec)
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

function createOption(value){
    const elemento = `
        <input type='checkbox' class='btn-check' value=${value} id='btn-check-2-outlined' checked autocomplete='off'>
        <label class='btn btn-outline-secondary' for='btn-check-2-outlined'>${value}</label>
    `
    return elemento
}