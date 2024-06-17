;(function () {
    SMultilangChoose = {
        init: function (params) {
            this.component = document.getElementById(params.component);
            this.page = params.curPage;
            this.signedParameters = params.signedParameters;
            this.initChoose();
        },


        initChoose: function () {
            const langList = this.component.querySelectorAll('[data-type="lang"]');
            if (!langList) {
                return;
            }

            langList.forEach(item => item.addEventListener('click', this.chooseLang.bind(this)))
        },

        chooseLang: async function (e) {
            const parent = e.target.closest('[data-type="lang"]');

            try {
                BX.showWait();
                const result = await BX.ajax.runComponentAction('sotbit:multilang.choose', 'chooseLang', {
                    mode: 'class',
                    data: {
                        lid: parent.dataset.id,
                        page: this.page
                    },
                    signedParameters: this.signedParameters
                });

                window.location.replace(result.data)

            } catch (error) {
                console.error(error);
            } finally {
                BX.closeWait();
            }
        },
    }
})();