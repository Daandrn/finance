const ticker        = document.querySelector('#ticker');
const stocks_id     = document.querySelector('#stocks_id');
const quantity      = document.querySelector('#quantity');
const average_value = document.querySelector('#average_value');

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

numberAndSeparatorValidKeys(average_value);

average_value.addEventListener('keypress', function (event) {
    const validAverage_value = /^\d{1,19}([\,\.]\d{0,2})?$/;

    let currentAverageValue = average_value.value + event.key;

    if (!validAverage_value.test(currentAverageValue)) {
        event.preventDefault();
    }
});

const confirmDeleteUserStock = document.querySelectorAll('#userStocksDelete').forEach(element => {
    element.addEventListener('submit', function (event) {
        let ticker = event.target[2].value;        

        let deleteUserStockConfirm = confirm(`Deseja realmente excluir a ação ${ticker}?`);

        if (!deleteUserStockConfirm) {
            event.preventDefault();
        }
    });
});