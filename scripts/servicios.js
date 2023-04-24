const exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget

    const recipient = button.getAttribute('data-bs-whatever')

    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalFooter = exampleModal.querySelector('.modal-footer')
    
    modalTitle.textContent = `${recipient}`
    
    if(button.innerText=="Editar"){
        const modalBodyInput = exampleModal.querySelectorAll('.modal-body input')

        console.log( button.getAttribute('data-id'));

        modalBodyInput[0].value = button.parentElement.parentElement.children[0].innerText;
        modalBodyInput[1].value = button.parentElement.parentElement.children[1].innerText;
        modalBodyInput[2].value = button.parentElement.parentElement.children[2].innerText;
        modalBodyInput[3].value = button.getAttribute('data-id');

        modalFooter.querySelector("input").name = `editar`
    }else if(button.innerText=="Añadir nuevo"){
        modalFooter.querySelector("input").name = `insertar`
    }
})