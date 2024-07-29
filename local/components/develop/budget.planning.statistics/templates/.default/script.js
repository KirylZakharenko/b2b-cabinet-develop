BX.namespace('BX.BudgetPlanningComponent');



(function () {
    'use strict';

    BX.BudgetPlanningComponent = {
        test: '',
        month: [],
        dataChart: {},
        chart: {},
        message: {},


        init: function (parameters) {
            this.test = parameters.test ?? '';
            this.month = parameters.month ?? [];
            this.dataChart = parameters.dataChart ?? [];
            this.message = parameters.message;

            this.month = this.convertObjectToArray(this.month);
            this.dataChart = this.convertObjectToArray(this.dataChart);

            this.drawChart("line");

            this.updateChart();

        },

        convertObjectToArray: function (object) {
            return Object.values(object);
        },

        updateChart: function () {
          let chartType = document.querySelector('#view-chart');

          chartType.addEventListener('change', (e) => {
              this.chart.destroy();
               this.drawChart(e.target.value);
          })
        },

        drawChart: function (typeChart) {
            const data = {
                labels: this.month,
                datasets: [{
                    label: this.message.CHART_LABEL,
                    data: this.dataChart,
                    backgroundColor: [
                        'rgb(255, 192, 203)',
                        'rgb(138, 43, 226)',
                        'rgb(255, 105, 180)',
                        'rgb(176, 224, 230)',
                        'rgb(147, 112, 219)',
                        'rgb(219, 112, 147)',
                        'rgb(165, 42, 42)',
                        'rgb(0, 0, 139)',
                        'rgb(128, 0, 128)',
                        'rgb(255, 0, 255)',
                        'rgb(218, 165, 32)',
                        'rgb(255, 160, 122)'
                    ],
                    fill: false,
                    // borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };
            this.chart = new Chart(myChart, {
                type: typeChart,
                data: data,
                options: {
                    scales: {
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }
    }
})();
