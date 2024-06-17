class JCSmartFilter {
  constructor(ajaxURL, viewMode, params) {
    this.ajaxURL = ajaxURL;
    this.form = null;
    this.timer = null;
    this.cacheKey = '';
    this.cache = [];
    this.popups = [];
    this.viewMode = viewMode;
    this.inputValues = [];
    this.params = params;

    if (params && params.SEF_SET_FILTER_URL) {
      this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
      this.sef = true;
    }
    if (params && params.SEF_DEL_FILTER_URL) {
      this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
    }

    // if (window.matchMedia("(max-width: 991px)").matches) {
    //   this.click($(".bx_filter form input[name=refresh_values]")[0]);
    // }

  }

  keyup(input) {
    if (!!this.timer) {
      clearTimeout(this.timer);
    }
    this.timer = setTimeout(BX.delegate(function () {
      this.reload(input);
    }, this), 500);
  };


  click(checkbox) {
    if (!!this.timer) {
      clearTimeout(this.timer);
    }

    this.timer = setTimeout(BX.delegate(function () {
      this.reload(checkbox);
    }, this), 500);
  };

  reload(input) {
    if (this.cacheKey !== '') {
      //Postprone backend query
      if (!!this.timer) {
        clearTimeout(this.timer);
      }
      this.timer = setTimeout(BX.delegate(function () {
        this.reload(input);
      }, this), 1000);
      return;
    }
    this.cacheKey = '|';

    this.position = BX.pos(input, true);
    this.form = BX.findParent(input, {'tag': 'form'});
    if (this.form) {
      var values = [];
      values[0] = {name: 'ajax', value: 'y'};
      this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

      this.inputValues = [];

      for (let prop in values) {
        if (values[prop].name.indexOf("arrFilter") != -1) {
          var tempName = values[prop].name;
          if (values[prop].value != 'Y' && values[prop].value != '' && values[prop].name.indexOf("MIN") == -1 && values[prop].name.indexOf("MAX") == -1) {
            tempName += '_' + values[prop].value;
          }

          this.inputValues[tempName] = values[prop].value;
        }
      }

      for (var i = 0; i < values.length; i++)
        this.cacheKey += values[i].name + ':' + values[i].value + '|';

      if (this.cache[this.cacheKey]) {
        this.curFilterinput = input;
        this.postHandler(this.cache[this.cacheKey], true);
      } else {
        if (this.sef) {
          var set_filter = BX('set_filter');
          set_filter.disabled = true;
        }

        this.curFilterinput = input;

        BX.ajax.loadJSON(
            this.ajaxURL,
            this.values2post(values),
            BX.delegate(this.postHandler, this)
        );
      }
      if(input.dataset.type && input.dataset.type === 'section') {
        let inputSubmit = document.createElement("input");
        inputSubmit.value = BX.message("form_submit");
        inputSubmit.name = 'set_filter';
        inputSubmit.type = 'hidden';
        this.form.appendChild(inputSubmit);
        this.form.submit();
      }
    }
  };

  updateItem(PID, arItem) {

    if (arItem.PROPERTY_TYPE === 'N' || arItem.PRICE) {
      var trackBar = window['trackBar' + PID];
      if (!trackBar && arItem.ENCODED_ID)
        trackBar = window['trackBar' + arItem.ENCODED_ID];

      if (trackBar && arItem.VALUES) {
        if (arItem.VALUES.MIN) {
          if (arItem.VALUES.MIN.FILTERED_VALUE)
            trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
          else
            trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
        }

        if (arItem.VALUES.MAX) {
          if (arItem.VALUES.MAX.FILTERED_VALUE)
            trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
          else
            trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
        }
      }
    } else if (arItem.VALUES) {
      for (var i in arItem.VALUES) {
        if (arItem.VALUES.hasOwnProperty(i)) {
          var value = arItem.VALUES[i];
          var control = BX(value.CONTROL_ID);
        }
      }
    }
  };

  postHandler (result, fromCache) {
    var hrefFILTER, url, curProp;
    var modef = BX('modef');
    var modef_num = BX('modef_num');

    if (!!result && !!result.ITEMS) {
      for (var popupId in this.popups) {
        if (this.popups.hasOwnProperty(popupId)) {
          this.popups[popupId].destroy();
        }
      }
      this.popups = [];

      for (var PID in result.ITEMS) {
        if (result.ITEMS.hasOwnProperty(PID)) {
          this.updateItem(PID, result.ITEMS[PID]);
        }
      }

      if (!!modef && !!modef_num) {
        modef_num.innerHTML = result.ELEMENT_COUNT;
        hrefFILTER = BX.findChildren(modef, {tag: 'A', class: "set_filter"}, true); // !!!

        if (result.FILTER_URL && hrefFILTER) {
          hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);
        }

        if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID) {
          BX.unbindAll(hrefFILTER[0]);
          BX.bind(hrefFILTER[0], 'click', function (e) {
            url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
            BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
            return BX.PreventDefault(e);
          });
        }

        if (result.COMPONENT_CONTAINER_ID) {
          url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
          BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
        } else {
          if (modef.style.display === 'none') {
            modef.style.display = 'inline-block';
          }

          if (this.viewMode == "VERTICAL") {
            curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class': 'bx_filter_parameters_box'}), {'class': 'bx_filter_container_modef'}, true, false);
            if (curProp)
              curProp.appendChild(modef);
          }

          if (result.SEF_SET_FILTER_URL) {
            this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
          }
        }
      }

      for (var codeProp in result.ITEMS) {
        var item = result.ITEMS[codeProp];
        for (var valueProp in item.VALUES) {
          if (this.inputValues.hasOwnProperty(item.VALUES[valueProp].CONTROL_ID)) {
            if (valueProp != "MIN" && valueProp != "MAX")
              this.inputValues[item.VALUES[valueProp].CONTROL_ID] = item.VALUES[valueProp].VALUE;
            else
              this.inputValues[item.VALUES[valueProp].CONTROL_ID] = BX.Currency.currencyFormat(item.VALUES[valueProp].HTML_VALUE, item.VALUES[valueProp].CURRENCY, true);
          }
        }
      }
    }

    if (this.sef) {
      var set_filter = BX('set_filter');
      set_filter.disabled = false;
    }

    if (!fromCache && this.cacheKey !== '') {
      this.cache[this.cacheKey] = result;
    }
    this.cacheKey = '';
  };

  bindUrlToButton(buttonId, url) {
    var button = BX(buttonId);
    if (button) {
      var proxy = function (j, func) {
        return function () {
          return func(j);
        }
      };

      if (button.type === 'submit')
        button.type = 'button';

      BX.bind(button, 'click', proxy(url, function (url) {
        window.location.href = url;
        return false;
      }));
    }
  };

  gatherInputsValues(values, elements) {
    if (elements) {
      for (var i = 0; i < elements.length; i++) {
        var el = elements[i];
        if (el.disabled || !el.type)
          continue;

        switch (el.type.toLowerCase()) {
          case 'text':
          case 'textarea':
          case 'password':
          case 'hidden':
          case 'select-one':
            if (el.value.length)
              values[values.length] = {name: el.name, value: el.value};
            break;
          case 'radio':
          case 'checkbox':
            if (el.checked)
              values[values.length] = {name: el.name, value: el.value};
            break;
          case 'select-multiple':
            for (var j = 0; j < el.options.length; j++) {
              if (el.options[j].selected)
                values[values.length] = {name: el.name, value: el.options[j].value};
            }
            break;
          default:
            break;
        }
      }
    }
  };

  values2post (values) {
    var post = [];
    var current = post;
    var i = 0;
    var url = '';
    var regExp = /arrFilter[\w\d]+/;

    while (i < values.length) {
      var p = values[i].name.indexOf('[');
      if (p === -1) {
        current[values[i].name] = values[i].value;
        current = post;
        i++;
      } else {
        var name = values[i].name.substring(0, p);
        var rest = values[i].name.substring(p + 1);
        if (!current[name])
          current[name] = [];

        var pp = rest.indexOf(']');
        if (pp === -1) {
          //Error - not balanced brackets
          current = post;
          i++;
        } else if (pp === 0) {
          //No index specified - so take the next integer
          current = current[name];
          values[i].name = '' + current.length;
        } else {
          //Now index name becomes and name and we go deeper into the array
          current = current[name];
          values[i].name = rest.substring(0, pp) + rest.substring(pp + 1);
        }
      }

      if (this.params.INSTANT_RELOAD && i !== values.length && values[i].name && regExp.test(values[i].name)) {
        if (url === '')
          url = location.origin + location.pathname + '?set_filter=Y&';

        url += values[i].name + "=" + values[i].value;
        if (i !== values.length - 1)
          url += '&';
      }
    }
    if (this.params.INSTANT_RELOAD && regExp.test(url))
      history.pushState({}, null, url);
    else if (this.params.INSTANT_RELOAD)
      history.pushState({}, null, location.origin + location.pathname);

    return post;

  };

  hideFilterProps(element) {
    var obj = element.parentNode,
        filterBlock = obj.querySelector("[data-role='bx_filter_block']"),
        propAngle = obj.querySelector("[data-role='prop_angle']");

    if (mql.matches) { // if filter is mobile

      var fb = $(filterBlock);
      var isCheckboxesWithPictures = fb.find(".checkboxes_with_pictures").length;

      if (isCheckboxesWithPictures)
        fb.find(".checkboxes_with_pictures span.bx-color-sl").each(function (i, elem) {
          $(this).after('<span class="color_value">' + $(this).find("span.bx-filter-btn-color-icon").attr("title") + '</span>');
        });

      fb.prepend('<div class="properties_block_title"><span>' + $(element).find("span.item_name").text() + '</span></div>').show("slide", {direction: "right"});

    } else {

      if (BX.hasClass(obj, "active")) {
        new BX.easing({
          duration: 300,
          start: {opacity: 1, height: filterBlock.offsetHeight},
          finish: {opacity: 0, height: 0},
          // transition : BX.easing.transitions.quart,
          step: function (state) {
            filterBlock.style.opacity = state.opacity;
            filterBlock.style.height = state.height + "px";
          },
          complete: function () {
            filterBlock.setAttribute("style", "");
            BX.removeClass(obj, "active");
          }
        }).animate();

        BX.addClass(propAngle, "fa-angle-down");
        BX.removeClass(propAngle, "fa-angle-up");
      } else {
        filterBlock.style.display = "block";
        filterBlock.style.opacity = 0;
        filterBlock.style.height = "auto";

        var obj_children_height = filterBlock.offsetHeight;

        filterBlock.style.height = 0;

        new BX.easing({
          duration: 300,
          start: {opacity: 0, height: 0},
          finish: {opacity: 1, height: obj_children_height},
          // transition : BX.easing.transitions.quart,
          step: function (state) {
            filterBlock.style.opacity = state.opacity;
            filterBlock.style.height = state.height + "px";
          },
          complete: function () {
          }
        }).animate();

        BX.addClass(obj, "active");
        BX.removeClass(propAngle, "fa-angle-down");
        BX.addClass(propAngle, "fa-angle-up");
      }

    }
  };

  showDropDownPopup (element, popupId) {
    var contentNode = element.querySelector('[data-role="dropdownContent"]');
    this.popups["smartFilterDropDown" + popupId] = BX.PopupWindowManager.create("smartFilterDropDown" + popupId, element, {
      autoHide: true,
      offsetLeft: 0,
      offsetTop: 3,
      overlay: false,
      draggable: {restrict: true},
      closeByEsc: true,
      content: BX.clone(contentNode),
      className: 'smartFilter-property-list'
    });
    this.popups["smartFilterDropDown" + popupId].show();
  };

  selectDropDownItem (element) {
    console.log(event.target)
    console.log(element.value);
    this.keyup(BX(element));
    return;

    var wrapContainer = BX.findParent(BX(controlId), {className: "bx-filter-select-container"}, false);

    var currentOption = wrapContainer.querySelector('[data-role="currentOption"]');
    currentOption.innerHTML = element.innerHTML;
    BX.PopupWindowManager.getCurrentPopup().close();
  };
}

BX.namespace("BX.Iblock.SmartFilter");
BX.Iblock.SmartFilter = (function () {
  /** @param {{
      containerSlider: string,
			minInputId: string,
			maxInputId: string,
			minPrice: float|int|string,
			maxPrice: float|int|string,
			curMinPrice: float|int|string,
			curMaxPrice: float|int|string,
			fltMinPrice: float|int|string|null,
			fltMaxPrice: float|int|string|null,
			precision: int|null,
			colorUnavailableActive: string,
			colorAvailableActive: string,
			colorAvailableInactive: string
		}} arParams
   */
  var SmartFilter = function (arParams) {
    if (typeof arParams === 'object') {
      this.containerSlider = BX(arParams.containerSlider);
      this.tracker = BX(arParams.tracker);
      this.trackerWrap = BX(arParams.trackerWrap);

      this.minInput = BX(arParams.minInputId);
      this.maxInput = BX(arParams.maxInputId);

      this.minPrice = parseFloat(arParams.minPrice);
      this.maxPrice = parseFloat(arParams.maxPrice);

      this.curMinPrice = parseFloat(arParams.curMinPrice);
      this.curMaxPrice = parseFloat(arParams.curMaxPrice);

      this.fltMinPrice = arParams.fltMinPrice ? parseFloat(arParams.fltMinPrice) : parseFloat(arParams.curMinPrice);
      this.fltMaxPrice = arParams.fltMaxPrice ? parseFloat(arParams.fltMaxPrice) : parseFloat(arParams.curMaxPrice);

      this.precision = arParams.precision || 0;

      this.priceDiff = this.maxPrice - this.minPrice;

      this.leftPercent = 0;
      this.rightPercent = 0;

      this.fltMinPercent = 0;
      this.fltMaxPercent = 0;

      this.filParamsFrom = arParams.FROM;

      //this.colorUnavailableActive = BX(arParams.colorUnavailableActive);//gray
      // this.colorAvailableActive = BX(arParams.colorAvailableActive);//blue
      // this.colorAvailableInactive = BX(arParams.colorAvailableInactive);//light blue

      this.isTouch = false;

      this.init();

      BX.bind(this.minInput, "keyup", BX.proxy(function (event) {
        this.onInputChange();
      }, this));

      BX.bind(this.maxInput, "keyup", BX.proxy(function (event) {
        this.onInputChange();
      }, this));
    }
  };

  SmartFilter.prototype.init = function () {
    var priceDiff;

    if (this.curMinPrice > this.minPrice) {
      priceDiff = this.curMinPrice - this.minPrice;
      this.leftPercent = (priceDiff * 100) / this.priceDiff;
    }

    this.setMinFilteredValue(this.fltMinPrice);


    if (this.curMaxPrice < this.maxPrice) {
      priceDiff = this.maxPrice - this.curMaxPrice;
      this.rightPercent = (priceDiff * 100) / this.priceDiff;
    }

    this.setMaxFilteredValue(this.fltMaxPrice);

    if (typeof this.containerSlider.noUiSlider === 'undefined' ) {
      noUiSlider.create(this.containerSlider, {
        start: [this.curMinPrice || this.fltMinPrice, this.curMaxPrice || this.fltMaxPrice],
        behaviour: 'drag',
        step: 1,
        connect: true,
        range: {
          'min': this.fltMinPrice,
          'max': this.fltMaxPrice
        }
      });
    }

    this.containerSlider.noUiSlider.on('change', function( values, handle ) {
      if (handle) {
        this.maxInput.value = Math.round(values[handle]);
        smartFilter.keyup(this.maxInput);
      } else {
        this.minInput.value = Math.round(values[handle]);
        smartFilter.keyup(this.minInput);
      }
    }.bind(this))
  };

  SmartFilter.prototype.setMinFilteredValue = function (fltMinPrice) {
    this.fltMinPrice = parseFloat(fltMinPrice);

    if (this.fltMinPrice >= this.minPrice) {
      var priceDiff = this.fltMinPrice - this.minPrice;
      this.fltMinPercent = (priceDiff * 100) / this.priceDiff;
      // if (this.colorAvailableActive) {
      //   if (this.leftPercent > this.fltMinPercent)
      //     this.colorAvailableActive.style.left = this.leftPercent + "%";
      //   else
      //     this.colorAvailableActive.style.left = this.fltMinPercent + "%";
      //
      //   this.colorAvailableInactive.style.left = this.fltMinPercent + "%";
      // }
    } else {
      // this.colorAvailableActive.style.left = "0%";
      // this.colorAvailableInactive.style.left = "0%";
    }
  };

  SmartFilter.prototype.setMaxFilteredValue = function (fltMaxPrice) {
    this.fltMaxPrice = parseFloat(fltMaxPrice);
    if (this.fltMaxPrice <= this.maxPrice) {
      var priceDiff = this.maxPrice - this.fltMaxPrice;
      this.fltMaxPercent = (priceDiff * 100) / this.priceDiff;
      // if (this.colorAvailableActive) {
      //   if (this.rightPercent > this.fltMaxPercent)
      //     this.colorAvailableActive.style.right = this.rightPercent + "%";
      //   else
      //     this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
      //
      //   this.colorAvailableInactive.style.right = this.fltMaxPercent + "%";
      // }
    } else {
      // this.colorAvailableActive.style.right = "0%";
      // this.colorAvailableInactive.style.right = "0%";
    }
  };

  SmartFilter.prototype.getXCoord = function (elem) {
    var box = elem.getBoundingClientRect();
    var body = document.body;
    var docElem = document.documentElement;

    var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
    var clientLeft = docElem.clientLeft || body.clientLeft || 0;
    var left = box.left + scrollLeft - clientLeft;

    return Math.round(left);
  };

  SmartFilter.prototype.getPageX = function (e) {
    e = e || window.event;
    var pageX = null;

    if (this.isTouch && event.targetTouches[0] != null) {
      pageX = e.targetTouches[0].pageX;
    } else if (e.pageX != null) {
      pageX = e.pageX;
    } else if (e.clientX != null) {
      var html = document.documentElement;
      var body = document.body;

      pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
      pageX -= html.clientLeft || 0;
    }

    return pageX;
  };

  SmartFilter.prototype.recountMinPrice = function () {
    var newMinPrice = (this.priceDiff * this.leftPercent) / 100;
    newMinPrice = (this.minPrice + newMinPrice).toFixed(this.precision);

    if (newMinPrice != this.minPrice)
      this.minInput.value = newMinPrice;
    else
      this.minInput.value = "";
    /** @global JCSmartFilter smartFilter */
    smartFilter.keyup(this.minInput);

    // this.minInput.parentNode.parentNode.parentNode.querySelector('.bx_ui_slider_part.p1 span').innerHTML = newMinPrice;

  };

  SmartFilter.prototype.recountMaxPrice = function () {
    var newMaxPrice = (this.priceDiff * this.rightPercent) / 100;
    newMaxPrice = (this.maxPrice - newMaxPrice).toFixed(this.precision);

    if (newMaxPrice != this.maxPrice)
      this.maxInput.value = newMaxPrice;
    else
      this.maxInput.value = "";
    /** @global JCSmartFilter smartFilter */
    smartFilter.keyup(this.maxInput);

    // this.maxInput.parentNode.parentNode.parentNode.querySelector('.bx_ui_slider_part.p5 span').innerHTML = newMaxPrice;
  };

  SmartFilter.prototype.onInputChange = function () {
    this.moveSlider();
  };

  SmartFilter.prototype.moveSlider = function () {
    this.containerSlider.noUiSlider.updateOptions({
      start: [this.minInput.value || this.fltMinPrice, this.maxInput.value || this.fltMaxPrice]
    })
  };

  SmartFilter.prototype.delay = function (calback) {
    if (!!timer) {
      clearTimeout(timer)
    }

    timer = setTimeout(calback.bind(this), 500);
  }

  return SmartFilter;
})();

function searchfieldRefresh() {
  $(".bx_filter_parameters_box").each(function (i, v) {
    if ($(this).find(".find_property_value").length) {
      filterList($(this).find(".find_property_value"), $(this).find(".blank_ul_wrapper"));
    }
  });
}

function filterList(header, list) {
  $(header).change(function () {
    var filter = $(header).val();

    if (filter) {
      $matches = $(list).find('label:Contains(' + filter + ')').parent();

      $('.bx_filter_parameters_box_checkbox', list).not($matches).slideUp();
      $matches.slideDown();
    } else {
      $(list).find(".bx_filter_parameters_box_checkbox").slideDown();
    }
    return false;
  }).keyup(function () {
    $(this).change();
  });
}

$(document).ready(function () {
  let filterBlock = document.querySelectorAll('.bx_filter .bx_filter_parameters_box');

  for (let n = 0; filterBlock.length > n; n++) {
    const search_wrap = filterBlock[n].querySelector(".find_property_value_wrapper");
    const search = filterBlock[n].querySelector(".find_property_value");
    const checkbox = filterBlock[n].querySelectorAll(".bx_filter_parameters_box_checkbox");
    const button_search = filterBlock[n].querySelector(".find_property_value__button-search");
    const button_close = filterBlock[n].querySelector(".find_property_value__button-close");
    const check_label = filterBlock[n].querySelectorAll(".form-check-label");
    const event_input = new Event("input");
    let search_string;

    if(search_wrap) {
      if(checkbox.length>7) {
        search_wrap.style.display = "block";

        button_search.addEventListener("click", function(e){
          e.preventDefault();
          search.focus();
          button_search.style.display = "none";
          button_close.style.display = "flex";
        });

        button_close.addEventListener("mousedown", function(e){
          e.preventDefault();
          search.focus();
          button_search.style.display = "flex";
          button_close.style.display = "none";
          search.value = "";
          search.dispatchEvent(event_input);
          search.blur();
        });

        search.addEventListener("focus", function(){
          button_search.style.display = "none";
          button_close.style.display = "flex";
        });

        search.addEventListener("blur", function(){
          button_search.style.display = "flex";
          button_close.style.display = "none";
        });

        if(check_label){

          let arr_check_label = Array.prototype.slice.call(check_label);

          search.addEventListener("input", function () {
            search_string = new RegExp(search.value, "i");

            arr_check_label.forEach(function(element){
              if(search_string.test(element.textContent)){
                element.parentNode.style.display = "block";
              }
              else {
                element.parentNode.style.display = "none";
              }
            });
          });
        }
      }
    }

    filterBlock[n].addEventListener('click', function () {
      setTimeout(setState, 2000);
    });
  }
});

function setState() {
  let filter = document.querySelector('.bx_filter');
  let filterItems = filter.querySelectorAll('.bx_filter_parameters_box');
  let stateFilter = [];
  let stateFilterJson = JSON.stringify(stateFilter);

  for (let i = 0; filterItems.length > i; i++) {
    var filterState = {};
    filterState.state = filterItems[i].getAttribute('class');
    filterState.dataPropid = filterItems[i].getAttribute('data-propid');
    stateFilter.push(filterState);
  }

    window.sessionStorage.setItem('item', stateFilterJson);
}

function getState() {
    if (window.sessionStorage.getItem('item')) {
        var stateFilter = JSON.parse(window.sessionStorage.getItem('item'));
        var filter = document.querySelector('.bx_filter');


        for (var i = 0; stateFilter.length > i; i++) {
            var data = '[data-propid =' + '"' + stateFilter[i].dataPropid + '"' + ']';
            var classItem = stateFilter[i].state;

            var item = filter.querySelector(data);

            if (item) {
                item.removeAttribute('class');
                item.setAttribute('class', classItem);
            }

        }
    }
}

window.addEventListener('DOMContentLoaded', function () {
    getState();
    setPerfectScrolls();
});

$(document).ready(setItemsCheckboxesEvents);

window.addEventListener("load", function () {
    fixFilterButtons();
});


function setItemsCheckboxesEvents() {
    let catalogSections = document.querySelectorAll(".catalog_section");

    for (let i = 0; i < catalogSections.length; i++) {
        let catalogSectionCheckBox = catalogSections[i].querySelector("a > .form-check input");
        let catalogSectionChildCheckBox = catalogSections[i].querySelectorAll("ul > .nav-item");
        const n = i;

        catalogSectionCheckBox.addEventListener("click", function () {
            checkAllCheckboxes(catalogSections[n]);
        });

        for (let k = 0; k < catalogSectionChildCheckBox.length; k++) {
            catalogSectionChildCheckBox[k].addEventListener("click", function () {
                checkAllChildCheckboxes(catalogSections[n]);
            });
            checkAllChildCheckboxes(catalogSections[n]);
        }

    }

    function checkAllCheckboxes(catalogSection) {
        let childrenCheckBoxes = catalogSection.querySelectorAll(".nav-group-sub .nav-item .form-check .form-check-label .uniform-checker > span");

        for (let i = 0; i < childrenCheckBoxes.length; i++) {
            let checkbox = childrenCheckBoxes[i].querySelector("input");

            if (catalogSection.querySelector("a > .form-check input").checked) {

                catalogSection.querySelector("a > .form-check .uniform-checker").setAttribute("indeterminate", "false");
                childrenCheckBoxes[i].classList.add("checked");
                checkbox.checked = true;

            } else {
                childrenCheckBoxes[i].classList.remove("checked");
                checkbox.checked = false;
            }
        }
    }

    function checkAllChildCheckboxes(catalogSection) {
        let childrenCheckBoxes = catalogSection.querySelectorAll(".nav-group-sub .nav-item .form-check .form-check-label .uniform-checker > span");
        let checked = 0;

        for (let i = 0; i < childrenCheckBoxes.length; i++) {
            if (childrenCheckBoxes[i].querySelector("input").checked) {
                checked++;
            }
        }

        if (childrenCheckBoxes.length === checked) {
            catalogSection.querySelector("a > .form-check .uniform-checker").setAttribute("indeterminate", "false");
            catalogSection.querySelector("a > .form-check input").classList.add("checked");
            catalogSection.querySelector("a > .form-check span").classList.add("checked");
            catalogSection.querySelector("a > .form-check input").checked = true;
        } else if (checked > 0) {
            catalogSection.querySelector("a > .form-check span").classList.remove("checked");
            catalogSection.querySelector("a > .form-check input").classList.remove("checked");
            catalogSection.querySelector("a > .form-check .uniform-checker").setAttribute("indeterminate", "true");
            catalogSection.querySelector("a > .form-check input").checked = false;
        } else {
            catalogSection.querySelector("a > .form-check input").classList.remove("checked");
            catalogSection.querySelector("a > .form-check span").classList.remove("checked");
            catalogSection.querySelector("a > .form-check .uniform-checker").setAttribute("indeterminate", "false");
        }
    }
}

function selectSize(element) {
    BX.toggleClass(element, 'border-text-warning');

    let parent = element.parentElement;
    let id = parent.querySelector("input").getAttribute("id");
    let label = parent.querySelector("label");

    smartFilter.keyup(BX(id));
    BX.toggleClass(label, 'bx-active');
}

function fixFilterButtons() {
    window.addEventListener("scroll", setFilterButtonsPos);
    window.addEventListener("resize", function () {
        let filter = document.querySelector(".index_blank-filter");

        document.querySelector(".row-under-modifications-filter").style.width = (filter.clientWidth - 2) + "px";
        setFilterButtonsPos();
    });

    setFilterButtonsPos();

    function setFilterButtonsPos() {
        if (document.querySelector('.row-under-modifications-filter')) {
            let topPos = $('.row-under-modifications-filter').offset().top;
            let filterButtonsWrapper = document.querySelector('.row-under-modifications-filter');

            if (topPos > window.innerHeight) {
                topPos = window.innerHeight;
            }

            let top = window.pageYOffset,
                pip = document.querySelector('.anchor_filter').getBoundingClientRect().top + window.pageYOffset,
                pip2 = document.querySelector('.anchor_header_filter').getBoundingClientRect().top + window.pageYOffset,
                height = filterButtonsWrapper.offsetHeight;


            if ((pip < top + height + topPos) || (pip2 + 100 > (top + $(window).height()))) {
                filterButtonsWrapper.classList.add('row-under-modifications-filter-fixed');
                filterButtonsWrapper.classList.remove('fixed-add-cart-animation');
            } else {
                if (top > pip - height) {
                    filterButtonsWrapper.classList.remove('row-under-modifications-filter-fixed');
                    filterButtonsWrapper.classList.add('fixed-add-cart-animation');
                } else {
                    filterButtonsWrapper.classList.remove('row-under-modifications-filter-fixed');
                    filterButtonsWrapper.classList.add('fixed-add-cart-animation');
                }
            }

            // let filter = document.querySelector(".index_blank-filter");
            //
            // filterButtonsWrapper.style.width = (filter.clientWidth - 2) + "px";
        }

    }
}

function setPerfectScrolls() {
  return;
    const scrolls = [];
    let perfectScrolls = document.querySelectorAll(".perfectscroll");
    for (let i = 0; i < perfectScrolls.length; i++) {
        scrolls[i] = new PerfectScrollbar(perfectScrolls[i], {
            wheelSpeed: 0.5,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}