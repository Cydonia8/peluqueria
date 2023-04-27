const exampleModal = document.getElementById('exampleModal')
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
        modalFooter.querySelector("input").name = `cancelar`;
        
        if(util.classList.contains("d-none")){
            util.classList.remove("d-none");
            inutil.forEach(ocultar=>{
                ocultar.classList.add("d-none");
            })
        }
    }
})