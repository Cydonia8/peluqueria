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

setTimeout(()=> {
    $(".alert").fadeTo(500, 0).slideUp(500, ()=>{
        $(this).remove(); 
    });
}, 3000);