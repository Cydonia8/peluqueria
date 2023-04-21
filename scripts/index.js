"use strict"
const botones = document.querySelectorAll("button")
const container_botones = document.querySelector(".buttons")
const container_forms = document.querySelector(".formularios")
const forms = document.querySelectorAll("form")
const atras = document.getElementById("atras")


botones.forEach(boton=>{
    boton.addEventListener("click", (event)=>{
        const pulsado = event.target
        let data = pulsado.getAttribute("data-button")
        container_botones.classList.add("hidden")
        if(container_forms.classList.contains("hidden")){
            container_forms.classList.remove("hidden")
        }
        forms.forEach(form=>{
            if(form.getAttribute("data-form") === data) {
                form.classList.add("active")
            }
        })
    })
})

atras.addEventListener("click",()=>{
    container_forms.classList.add("hidden")
    container_botones.classList.remove("hidden")
    forms.forEach(form=>{form.classList.remove("active")})
})