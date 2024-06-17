const tax = document.querySelector('#tax');
const value_buy = document.querySelector('#value_buy');
const value_current = document.querySelector('#value_current');
const taxDiv = document.querySelector('#taxDiv');
const modality_id = document.querySelector('#modality_id');

const modality_id_change = document.querySelector('#modality_id').addEventListener('change', function () {
    var optionsTaxDisabled = ["4", "6"];

    if (optionsTaxDisabled.includes(modality_id.value)) {
        tax.disabled = true;
        tax.value = null;
        taxDiv.style.display = "none";
        return;
    }

    tax.disabled = false;
    taxDiv.style.display = "";
});

function numberAndSeparatorValidKeys(element) {
    const validKeys = RegExp('[0-9.,]');
    element.addEventListener('keypress', function (event) {
        if (!validKeys.test(event.key)) {
           event.preventDefault(); 
        }
    });
}

numberAndSeparatorValidKeys(tax);
numberAndSeparatorValidKeys(value_buy);
numberAndSeparatorValidKeys(value_current);

tax.addEventListener('keypress', function (event) {
    const validValueTax = /^\d{1,2}([\,\.]\d{0,2})?$/;

    let currentTax = tax.value + event.key;

    if (!validValueTax.test(currentTax)) {
        event.preventDefault();
    }
    
    if (tax.value.length >= 5) {
        event.preventDefault();
    }
});

value_buy.addEventListener('keypress', function (event) {
    const validValueBuy = /^\d{1,19}([\,\.]\d{0,2})?$/;

    let currentValueBuy = value_buy.value + event.key;

    if (!validValueBuy.test(currentValueBuy)) {
        event.preventDefault();
    }
});
