"use strict"
const botones = document.querySelectorAll("button")
const container_botones = document.querySelector(".buttons")
const container_forms = document.querySelector(".formularios")
const forms = document.querySelectorAll("form")

botones.forEach(boton=>{
    boton.addEventListener("click", (event)=>{
        const pulsado = event.target
        let data = pulsado.getAttribute("data-button")
        container_botones.style.display = "none"
        forms.forEach(form=>{
            if(form.getAttribute("data-form") === data) {
                form.classList.add("active")
            }
        })
    })
})
