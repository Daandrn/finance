const inputTicker = document.querySelector('#ticker');
const inputName = document.querySelector('#name');

const newStocks = document.querySelector('#newStocks').addEventListener('click', function () {
    const newStocksModal = document.querySelector('#newStocksModal');

    newStocksModal.disabled = false;
    newStocksModal.readOnly = true;
    inputTicker.required = true;
    inputName.required = true;
    newStocksModal.style = "display: flex; position: absolute; top: 0; left: 0; topper: 0; width: 100%; height: 100%; z-index: 5; justify-content: center; background-color: rgba(100, 100, 100, 0.2);";

});

const fecharNewStocks = document.querySelector('#fecharNewStocks').addEventListener('click', function () {
    const newStocksModal = document.querySelector('#newStocksModal');

    newStocksModal.disabled = true;
    newStocksModal.readOnly = false;
    newStocksModal.style.display = "none";
});

const confirmDeleteStock = document.querySelectorAll('#deleteStock').forEach(element => {
    element.addEventListener('submit', function (event) {
        let ticker = event.target[2].value;        
        
        let deleteStockConfirm = confirm(`Deseja realmente excluir ${ticker}?`);
    
        if (!deleteStockConfirm) {
            event.preventDefault();
        }
    });
});

function updateStocksValues() {
    const loadingModal = document.querySelector('#loadingModal');

    loadingModal.style.display = '';
    
    fetch('acoes/atualizarValores',{
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response =>
        response.json()
    )
    .then(data =>
        alert(data.message)
    )
    .catch(error => {
        alert('Erro durante a requisição');
        console.error('Erro durante a requisição.', error);
    })
    .finally(() =>{
        loadingModal.style.display = 'none';

        window.location.reload();
    });
}

const btUpdateValues = document.querySelector('#btUpdateValues');

if (btUpdateValues) {
    btUpdateValues.addEventListener('click', updateStocksValues);
}
