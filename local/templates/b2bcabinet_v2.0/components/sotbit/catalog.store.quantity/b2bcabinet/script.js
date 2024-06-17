(function (window){
    'use strict';

    if (window.JCSotbitStoreAmount)
        return;

	window.JCSotbitStoreAmount = function (params) {
		this.obName = params.obName;
		this.html = params.html;
		this.node = document.getElementById(this.obName);
		this.popup = null;
	}

	window.JCSotbitStoreAmount.prototype = {
		init: function() {
			let info = this.node;

			if (info) {
				this.setEventAvaliable(info);
			}
		},

		setEventAvaliable: function(node) {
			new bootstrap.Tooltip(node, {
				title: this.html,
				html: true,
				placement: 'right'
			})
		},
	}
})(window)