const insertar = document.getElementById("button-insertar-empleado")
const section = document.querySelector(".insertar-empleado")
const cerrar = document.getElementById("close-form-empleado")

insertar.addEventListener("click", ()=>{
    if(section.classList.contains("mostrar")){
        section.classList.remove("mostrar")
    }else{
        section.classList.add("mostrar")
    }
})

cerrar.addEventListener("click", ()=>{
    section.classList.remove("mostrar")
})

setTimeout(()=> {
    $(".alert").fadeTo(500, 0).slideUp(500, ()=>{
        $(this).remove(); 
    });
}, 3000);