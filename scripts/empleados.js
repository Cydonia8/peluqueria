const inicio_m = document.getElementById("inicio_m")
const fin_m = document.getElementById("fin_m")
const inicio_t = document.getElementById("inicio_t")
const fin_t = document.getElementById("fin_t")
const inicio_desact = document.getElementById("inicio_desact")
const button_des = document.querySelectorAll(".programar-des")
const id_js = document.querySelector(".id-js")

button_des.forEach(button=>{
    button.addEventListener("click", (evt)=>{
        let id = evt.target.getAttribute("data-id")
        id_js.setAttribute("value", id)
    })
})


let today = new Date()
let dia = formatDate(today.getDate())
let mes = formatDate(today.getMonth()+1)
let anio = today.getFullYear()
let fecha_actual = `${anio}-${mes}-${dia}`
// let fecha_minima_activacion = 
inicio_desact.setAttribute("min", fecha_actual)
inicio_desact.value=fecha_actual

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

setTimeout(()=> {
    $(".alert").fadeTo(500, 0).slideUp(500, ()=>{
        $(this).remove(); 
    });
}, 3000);

function formatDate(fecha){
    if(fecha < 10){
        return `0${fecha}`
    }else{
        return fecha
    }
}
const exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget

    const recipient = button.getAttribute('data-bs-whatever')

    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalFooter = exampleModal.querySelector('.modal-footer')
    
    modalTitle.textContent = `${recipient}`
    
    const modalBodyInput = exampleModal.querySelectorAll('.modal-body input');
    if(button.innerText=="Editar"){
        const fila=button.parentElement.parentElement;

        modalBodyInput[0].value = fila.children[0].innerText;
        modalBodyInput[1].value = fila.children[1].innerText;
        modalBodyInput[2].value = fila.children[2].innerText;
        modalBodyInput[3].removeAttribute("required");
        const horas=fila.children[3].innerText;

        if(fila.children[3].getAttribute("data-rango")=="dia"){
            const m=horas.split("/")[0];
            const t=horas.split("/")[1];
            modalBodyInput[4].value = m.split("-")[0];
            modalBodyInput[5].value = m.split("-")[1];
            modalBodyInput[6].value = t.split("-")[0];
            modalBodyInput[7].value = t.split("-")[1];
            fin_m.removeAttribute("disabled");
            fin_t.removeAttribute("disabled");
        }else if(fila.children[3].getAttribute("data-rango")=="mañana"){
            modalBodyInput[4].value = horas.split("-")[0];
            modalBodyInput[5].value = horas.split("-")[1];
            fin_m.removeAttribute("disabled");
        }else if(fila.children[3].getAttribute("data-rango")=="tarde"){
            modalBodyInput[6].value = horas.split("-")[0];
            modalBodyInput[7].value = horas.split("-")[1];
            fin_t.removeAttribute("disabled");
        }

        modalBodyInput[modalBodyInput.length-1].value = button.getAttribute('data-id');
        modalFooter.querySelector("input").name = `editar`
    }else if(button.innerText=="Añadir nuevo"){
        modalBodyInput[3].setAttribute("required",true);
        fin_m.setAttribute("disabled",true);
        fin_t.setAttribute("disabled",true);
        modalBodyInput.forEach(campo=>campo.value="");
        modalFooter.querySelector("input").name = `insertar`
    }
})
