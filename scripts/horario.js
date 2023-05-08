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