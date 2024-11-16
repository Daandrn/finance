const createUserStockMovement = document.querySelector('#createUserStockMovement');

if (createUserStockMovement != undefined) {
    const ticker    = document.querySelector('#ticker');
    const stocks_id = document.querySelector('#stocks_id');
    const quantity  = document.querySelector('#quantity');
    const value_buy = document.querySelector('#value');
    
    ticker.addEventListener('blur', function (event) {
        Array.from(stocks_id.options).forEach(option => {
            option.selected = false;

            if (ticker.value.toUpperCase() == option.dataset.ticker) {
                option.selected = true;
            }
        });
    });
    
    stocks_id.addEventListener('change', function (event) {
        ticker.value = stocks_id.options[stocks_id.options.selectedIndex].dataset.ticker ?? '';
    })

    quantity.addEventListener('keypress', (event) => {

        alert('esta errado aqui corrigir no arquivo: userStocksMovment.js');
        
        const validKeys = RegExp('[0-9]');
        if (!validKeys.test(event.key)) {
            event.preventDefault();

            alert('Digite apenas números!');
        }

        if (
            quantity.value.length > 9
            && validKeys.test(event.key)
        ) {
            event.preventDefault();

            alert('Informe no máximo 10 dígitos!');
        }
    });

    function numberAndSeparatorValidKeys(element) {
        const validKeys = RegExp('[0-9.,]');
        element.addEventListener('keypress', function (event) {
            if (!validKeys.test(event.key)) {
               event.preventDefault(); 
            }
        });
    }

    numberAndSeparatorValidKeys(value_buy);

    value_buy.addEventListener('keypress', function (event) {
        const validvalue_buy = /^\d{1,19}([\,\.]\d{0,2})?$/;

        let currentAverageValue = value_buy.value + event.key;

        if (!validvalue_buy.test(currentAverageValue)) {
            event.preventDefault();
        }
    });
}

const verifyStockMovementDelete = document.querySelector('#userStocksMovementDelete');
verifyStockMovementDelete.addEventListener('submit', function (event) {
    const values = document.querySelectorAll('input[type="checkbox"]:checked');

    if (values.length < 1) {
        alert('Nenhum movimento selecionado!');

        event.preventDefault();

        return;
    }

    let deleteUserStockMovementConfirm = confirm(`Deseja realmente excluir a(s) movimentação(ões) selecionada(s)?`);

    if (!deleteUserStockMovementConfirm) {
        event.preventDefault();
    }
});

const userStocksMovement_import = document.querySelector('#userStocksMovement_import');

if (userStocksMovement_import != undefined) {
    userStocksMovement_import.addEventListener('submit', function () {
        document.querySelector('#loadingModal').style.display = '';
    });
}

const selectAllMovements = document.querySelector('#selectAllMovements');

selectAllMovements.addEventListener('click', function () {
    const movementsChecked = document.querySelectorAll('input[type="checkbox"]:checked');
    const movements = document.querySelectorAll('input[type="checkbox"]');

    let numMovementsChecked = movementsChecked.length;
    let numMovements = movements.length;
    
    movements.forEach(item => {
        if (numMovementsChecked == numMovements) {
            item.checked = false;

            return;
        }
        
        if (!item.checked) {
            item.checked = true;

        }
    });
});
