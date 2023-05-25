"use strict"
const boton=document.querySelectorAll(".mod");

boton.forEach((b)=>{
    const campos=b.parentElement.parentElement.querySelectorAll("input:not([type=submit])");
    b.addEventListener("click", (event)=>{
        const pulsado = event.target;
        pulsado.classList.remove("d-block");
        pulsado.classList.add("d-none");
        pulsado.nextElementSibling.classList.remove("d-none");
        pulsado.nextElementSibling.classList.add("d-block");
        campos.forEach(campo=>{
            campo.removeAttribute("disabled");
        })
    })
})

const exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget

    const recipient = button.getAttribute('data-bs-whatever')

    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalFooter = exampleModal.querySelector('.modal-footer')
    
    modalTitle.textContent = `${recipient}`
    
    if(button.innerText=="Editar"){
        const modalBodyInput = exampleModal.querySelectorAll('.modal-body input')
        const fila=button.parentElement.parentElement;

        modalBodyInput[0].value = fila.children[0].innerText;
        if(fila.children[1].innerText!='-'){
            modalBodyInput[1].value = fila.children[1].innerText.split("-")[0];
            modalBodyInput[2].value = fila.children[1].innerText.split("-")[1];
            fin_m.removeAttribute("disabled");
        }
        if(fila.children[2].innerText!='-'){
            modalBodyInput[3].value = fila.children[2].innerText.split("-")[0];
            modalBodyInput[4].value = fila.children[2].innerText.split("-")[1];
            fin_t.removeAttribute("disabled");
        }
        modalBodyInput[modalBodyInput.length-1].value = button.getAttribute('data-id');

        modalFooter.querySelector("input").name = `editar`
    }else if(button.innerText=="Añadir nuevo"){
        fin_m.setAttribute("disabled",true);
        fin_t.setAttribute("disabled",true);
        modalFooter.querySelector("input").name = `insertar`
    }
})

const inicio_m = document.getElementById("inicio_m")
const fin_m = document.getElementById("fin_m")
const inicio_t = document.getElementById("inicio_t")
const fin_t = document.getElementById("fin_t")

inicio_m.addEventListener("change", ()=>{
    let minimo = inicio_m.value
    fin_m.setAttribute("min", minimo)
    fin_m.removeAttribute("disabled")
})

inicio_t.addEventListener("change", ()=>{
    let minimo = inicio_t.value
    fin_t.setAttribute("min", minimo)
    fin_t.removeAttribute("disabled")
})

const array_dias = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']
const array_dias_comp = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']

function responsiveCalendario(width){
    if(width.matches){
        dias_semana.forEach((dia, index)=>{
            dia.innerText=array_dias[index]
        })
    }else{
        dias_semana.forEach((dia, index)=>{
            dia.innerText=array_dias_comp[index]
        })
    }
}

const max_width = window.matchMedia("(max-width: 1100px)")
const dias_semana=document.getElementById("dias_semana").querySelectorAll("label");

responsiveCalendario(max_width)
window.addEventListener("resize", ()=>{
    responsiveCalendario(max_width)
})

const select_dias=document.getElementById("select_dias");
select_dias.addEventListener("change",()=>{
    horario_diario(select_dias.value);
})

async function horario_diario(valor){
    const respuesta = await fetch('../php/api_fechas_horas.php');
    const datos = await respuesta.json();
    const lista=datos["datos"].horario;
    const manana=select_dias.parentElement.nextElementSibling.querySelectorAll("input");
    const tarde=select_dias.parentElement.nextElementSibling.nextElementSibling.querySelectorAll("input");
    
    if(valor!=0){
        const sele=lista.find(dia=>dia.id==valor);
        manana[0].value=sele.m_apertura;
        manana[1].value=sele.m_cierre;
        tarde[0].value=sele.t_apertura;
        tarde[1].value=sele.t_cierre;
    }else{
        manana.forEach(a=>a.value="");
        tarde.forEach(a=>a.value="");
    }
}