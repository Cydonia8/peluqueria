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