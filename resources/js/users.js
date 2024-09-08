const confirmUserDelete = document.querySelectorAll('#userDelete').forEach(element => {
    element.addEventListener('submit', function (event) {
        let nameUserDelete = event.target[2].value;
        let userConfirm = confirm(`Deseja realmente excluir o usuário ${nameUserDelete}?`);

        if (!userConfirm) {
            event.preventDefault();
        }
    });
});