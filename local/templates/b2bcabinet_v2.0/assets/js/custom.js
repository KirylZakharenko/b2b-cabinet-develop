var currencySelector = document.querySelector('#currency-choise');
currencySelector.addEventListener('change', function (event) {
    var currency = {
        CURRENCY: event.target.value
    }

    BX.ajax({
        url: '/include/ajax/change_currency.php',
        data: currency,
        method: 'POST',
        dataType: 'json',
        onsuccess: function(data) {
            location.reload();
        },
        onfailure: function(data) {
            console.error('Не получилось сменить валюту на сайте')
        }
    });
})