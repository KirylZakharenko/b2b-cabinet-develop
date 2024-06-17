var currencySelector = document.querySelector('#currency-choise');

currencySelector.addEventListener('change', function (event) {
    var currency = {
        CURRENCY: event.target.value
    }

    BX.ajax({
        url: '/include/ajax/change_currency.php', // адрес на который передаются данные с формы
        data: currency,
        method: 'POST', // метод передачи данных POST или GET
        dataType: 'json', // тип передаваемых данных
        onsuccess: function(data) { // в случаи успеха, выполняем действия
            console.log(data); //выводим полученные данные в результате успеха.
        },
        onfailure: function(data) { // действия в случаи ошибки
            console.error(data) // выводим в результате ошибки, сообщение об ошибки
        }
    });
})