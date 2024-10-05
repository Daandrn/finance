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

const confirmDeleteUserStockMovement = document.querySelectorAll('#userStocksMovementDelete').forEach(element => {
    element.addEventListener('submit', function (event) {
        let movementId = event.target[2].value;        

        let deleteUserStockMovementConfirm = confirm(`Deseja realmente excluir a movimentação ${movementId}?`);

        if (!deleteUserStockMovementConfirm) {
            event.preventDefault();
        }
    });
});

const userStocksMovement_import = document.querySelector('#userStocksMovement_import');

if (userStocksMovement_import != undefined) {
    userStocksMovement_import.addEventListener('submit', function () {
        document.querySelector('#loadingModal').style.display = '';
    });
}