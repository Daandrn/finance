const inputDescription = document.querySelector('#description');

const newModality = document.querySelector('#newModality').addEventListener('click', function () {
    const newModalityModal = document.querySelector('#newModalityModal');

    newModalityModal.disabled = false;
    newModalityModal.readOnly = true;
    inputDescription.required = true;
    newModalityModal.style = "display: flex; position: absolute; top: 0; left: 0; topper: 0; width: 100%; height: 100%; z-index: 5; justify-content: center; background-color: rgba(100, 100, 100, 0.2);";

});

const fecharNewModality = document.querySelector('#fecharNewModality').addEventListener('click', function () {
    const newModalityModal = document.querySelector('#newModalityModal');

    newModalityModal.disabled = true;
    newModalityModal.readOnly = false;
    newModalityModal.style.display = "none";
});
