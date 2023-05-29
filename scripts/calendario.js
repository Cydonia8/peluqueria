const exampleModal = document.getElementById('exampleModal')
const btn_close = document.querySelector(".btn-close")
const btn_close_modal = document.querySelector(".modal-footer button")
const input_date = document.querySelector("input[type=date]")
const calendario = document.querySelectorAll("#calendario td:not(:empty)")
const flechas = document.querySelectorAll("caption a")
const max_width = window.matchMedia("(max-width: 650px)")
const cabecera_dias = document.querySelectorAll(".cabecera-dia")

const array_dias = ['L', 'M', 'X', 'J', 'V', 'S', 'D']
const array_dias_comp = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
let fiestas;
let today = new Date()
let dia = formatDate(today.getDate())
let mes = formatDate(today.getMonth()+1)
let anio = today.getFullYear()
let fecha_completa = `${anio}-${mes}-${dia}`

input_date.setAttribute("min", fecha_completa)

btn_close_modal.addEventListener("click", ()=>{
    location.reload()
})

btn_close.addEventListener("click", ()=>{
    location.reload()
})
festivos()
responsiveCalendario(max_width)
window.addEventListener("resize", ()=>{
    responsiveCalendario(max_width)
})

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

function responsiveCalendario(width){
    if(width.matches){
        cabecera_dias.forEach((dia, index)=>{
            dia.innerText=array_dias[index]
        })
    }else{
        cabecera_dias.forEach((dia, index)=>{
            dia.innerText=array_dias_comp[index]
        })
    }
}
flechas.forEach(flecha=>{
    flecha.addEventListener("click", comprobarFestivos)
})

exampleModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget

    const recipient = button.getAttribute('data-bs-whatever')

    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalFooter = exampleModal.querySelector('.modal-footer')
    const inutil=exampleModal.querySelectorAll(".no-delete");
    const util=exampleModal.querySelector(".delete");

    if(!util.classList.contains("d-none")){
        util.classList.add("d-none");
        inutil.forEach(ocultar=>{
            ocultar.classList.remove("d-none");
        })
    }
    
    modalTitle.textContent = `${recipient}`
    
    if(button.innerText=="Editar"){
        const modalBodyInput = exampleModal.querySelectorAll('.modal-body input')
        const modalBodySelect = exampleModal.querySelectorAll('.modal-body select')

        modalBodySelect[0].value = button.parentElement.parentElement.children[3].getAttribute('data-id');
        modalBodySelect[1].value = button.parentElement.parentElement.children[4].getAttribute('data-id');
        modalBodySelect[2].value = button.parentElement.parentElement.children[1].innerText;;
        modalBodyInput[0].value = button.parentElement.parentElement.children[0].getAttribute('data-fecha');
        modalBodyInput[1].value = button.parentElement.parentElement.children[2].getAttribute('data-id');
        modalBodyInput[2].value = button.parentElement.parentElement.children[3].getAttribute('data-id');
        modalBodyInput[3].value = button.parentElement.parentElement.children[0].getAttribute('data-fecha');
        modalBodyInput[4].value = button.parentElement.parentElement.children[1].innerText;

        modalFooter.querySelector("input").name = `editar`
    }else if(button.innerText=="Añadir nuevo"){
        modalFooter.querySelector("input").name = `insertar`
    }else if(button.innerText=="Cancelar"){
        const modalBodyInput = exampleModal.querySelectorAll('.modal-body input')
        modalBodyInput[1].value = button.parentElement.parentElement.children[2].getAttribute('data-id');
        modalBodyInput[2].value = button.parentElement.parentElement.children[3].getAttribute('data-id');
        modalBodyInput[3].value = button.parentElement.parentElement.children[0].getAttribute('data-fecha');
        modalBodyInput[4].value = button.parentElement.parentElement.children[1].innerText;

        modalFooter.querySelector("input").name = `cancelar`;
        
        if(util.classList.contains("d-none")){
            util.classList.remove("d-none");
            inutil.forEach(ocultar=>{
                ocultar.classList.add("d-none");
            })
        }
    }
})

function formatDate(fecha){
    if(fecha < 10){
        return `0${fecha}`
    }else{
        return fecha
    }
}

setTimeout(()=> {
    $(".alert").fadeTo(500, 0).slideUp(500, ()=>{
        $(this).remove(); 
    });
}, 3000);


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

const tabla_citas=document.querySelectorAll("table")[1];
if(tabla_citas.querySelector("tbody>tr>td").innerText=="No hay citas para hoy"){
    console.log(tabla_citas.parentElement.previousElementSibling.children[0].classList.add("desactivado"));
}