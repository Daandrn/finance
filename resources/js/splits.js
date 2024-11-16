const grouping = document.querySelector('#grouping');
const split = document.querySelector('#split');

grouping.addEventListener('keypress', function (event) {
    const validInput = /[\d]/;
    
    if (!validInput.test(event.key)) {
        event.preventDefault();

        alert('Informe apenas números!');
    }

    if (grouping.value.length > 9) {
        event.preventDefault();

        alert('Tamanho máximo: 9 digitos');
    }
    
    split.disabled = true;
    split.value = '';
});

grouping.addEventListener('blur', function () {
    if (grouping.value == '') {
        split.disabled = false;
    }
});

split.addEventListener('keypress', function (event) {
    const validInput = /[\d]/;
    
    if (!validInput.test(event.key)) {
        event.preventDefault();

        alert('Informe apenas números!');
    }

    if (split.value.length > 9) {
        event.preventDefault();

        alert('Tamanho máximo: 9 digitos');
    }
    
    grouping.disabled = true;
    grouping.value = '';
});

split.addEventListener('blur', function () {
    if (split.value == '') {
        grouping.disabled = false;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    if (split.value.length > 0) {
        grouping.disabled = true;
    }

    if (grouping.value.length > 0) {
        split.disabled = true;
    }

    const splitTableBody = this.querySelector('#splitTableBody');

    let stocks_id = this.querySelector('#stocks_id').value;

    fetch(`/administrador/agrupamento/${stocks_id}`,{
        method: 'GET',
        headers: {
            'content-type': 'application/json'
        },
    })
    .then(response => 
        response.json()
    )
    .then(data => {
        let splits = JSON.parse(data.body);
        let tBody = '';

        splits.length < 1 
            ? tBody = 
                `<tr>
                    <td colspan="4">Não há Agrupamentos/Desdobramentos!</td>
                </tr>`
            : splits.forEach(split => {
            let date = new Date(split.date);
            date = new Intl.DateTimeFormat('pt-BR').format(date);
            
            tBody += `
                <tr>
                    <td>
                        <input type="checkbox" name="splits[]" id="" value="${ split.id }" />
                    </td>
                    <td>
                        ${ split.grouping < 1 ? '-' : split.grouping }
                    </td>
                    <td>
                        ${ split.split < 1 ? '-' : split.split }
                    </td>
                    <td>
                        ${ date }
                    </td>
                </tr>
            `;
        });

        splitTableBody.innerHTML += tBody;

        return;
    })
    .catch(error => {
        alert('Erro aqui: ' + error.message);

        return;
    });
});
