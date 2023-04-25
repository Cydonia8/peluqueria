"use strict"
const boton = document.getElementById("mod");
const campos=document.querySelectorAll("input[type='time']");



boton.addEventListener("click", (event)=>{
    const pulsado = event.target;
    pulsado.classList.remove("d-block");
    pulsado.classList.add("d-none");
    pulsado.nextElementSibling.classList.remove("d-none");
    pulsado.nextElementSibling.classList.add("d-block");
    campos.forEach(campo=>{
        campo.removeAttribute("disabled");
    })
        
})