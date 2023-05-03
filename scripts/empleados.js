setTimeout(()=> {
    $(".alert").fadeTo(500, 0).slideUp(500, ()=>{
        $(this).remove(); 
    });
}, 3000);

const exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget

    const recipient = button.getAttribute('data-bs-whatever')

    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalFooter = exampleModal.querySelector('.modal-footer')
    
    modalTitle.textContent = `${recipient}`
    
    const modalBodyInput = exampleModal.querySelectorAll('.modal-body input');
    if(button.innerText=="Editar"){
        modalBodyInput[0].value = button.parentElement.parentElement.children[0].innerText;
        modalBodyInput[1].value = button.parentElement.parentElement.children[1].innerText;
        modalBodyInput[2].value = button.parentElement.parentElement.children[2].innerText;
        modalBodyInput[modalBodyInput.length-1].value = button.getAttribute('data-id');

        modalFooter.querySelector("input").name = `editar`
    }else if(button.innerText=="Añadir nuevo"){
        modalBodyInput.forEach(campo=>campo.value="");
        modalFooter.querySelector("input").name = `insertar`
    }
})