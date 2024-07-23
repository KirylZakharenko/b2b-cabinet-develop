BX.namespace('BX.BudgetPlanningComponent');

(function () {
    'use strict';

    BX.BudgetPlanningComponent = {
        test: '',


        init: function (parameters) {
            this.test = parameters.test

        }

    }






})();
const data = {
    labels: ['Март','Март','Март','Март','Март','Март','Март','Март',],
    datasets: [{
        label: 'My First Dataset',
        data: [65, 59, 80, 81, 56, 55, 40],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
};
const stackedLine = new Chart(myChart, {
    type: 'line',
    data: data,
    options: {
        scales: {
            y: {
                stacked: true
            }
        }
    }
});