
Number.prototype.formatMoney = function(c, d, t){
   var n = this,
       c = isNaN(c = Math.abs(c)) ? 2 : c,
       d = d == undefined ? "," : d,
       t = t == undefined ? "." : t,
       s = n < 0 ? "-" : "",
       i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
       j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "")
            + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t)
            + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

Date.prototype.addHoras = function(horas){
    this.setHours(this.getHours() + horas);
    return new Date(this.getTime());
};
Date.prototype.addMinutos = function(minutos){
    this.setMinutes(this.getMinutes() + minutos);
    return new Date(this.getTime());
};
Date.prototype.addSegundos = function(segundos){
    this.setSeconds(this.getSeconds() + segundos);
    return new Date(this.getTime());
};
Date.prototype.addDias = function(dias){
    this.setDate(this.getDate() + dias);
    return new Date(this.getTime());
};
Date.prototype.addMeses = function(meses){
    this.setMonth(this.getMonth() + meses);
    return new Date(this.getTime());
};
Date.prototype.addAnos = function(anos){
    this.setYear(this.getFullYear() + anos);
    return new Date(this.getTime());
};

 var NUrl = {

   viewProg: function(a) {
      window.event.stopPropagation();
      window.event.preventDefault();
      location.href = Shell.urlRoot + "?acao=" + a;
   },

   viewLista: function(a)     { location.href = Shell.urlRoot + "?mode=list&acao=" + a; },
   viewCad: function(a, c)    { location.href = Shell.urlRoot + "?mode=edit&acao=" + a + "&cod=" + c; },
   newCad: function(a)        { location.href = Shell.urlRoot + "?mode=new&acao="  + a; },

   deleteCad: function(conceito, valor) {
      if(!confirm("Deseja realmente excluir " + conceito + " `" + valor + "` ?")) {
         return false;
      }
      $("#mode").val("delete");
      Shell.enableForm();
      Shell.disableEditingWarning();
      $("#formCad")[0].submit(); 
   }

 };
var TableSort = {

   sortAsc: 'sort-asc',
   sortDesc: 'sort-desc',

   init: function() {
      $('table.sort th').each(function() {
         if($.trim($(this).text()) == '') return;
         $(this).addClass('hand');
      });
      $('table.sort th').click(function() {
         if($.trim($(this).text()) == '') return;
         var table = $(this).parents('table').eq(0);
         var rows = table.find('tbody tr:gt(0)').toArray().sort(TableSort.comparer($(this).index()));
         this.asc = !this.asc;
         if(!this.asc) rows = rows.reverse();
         for(var i = 0; i < rows.length; i++){ table.append(rows[i]); }
         if(this.asc)
              $(this).removeClass(TableSort.sortDesc).addClass(TableSort.sortAsc);
         else $(this).removeClass(TableSort.sortAsc).addClass(TableSort.sortDesc);
      });
   },

   comparer: function(index) {
      return function(a, b) {
         var valA = TableSort.getCellValue(a, index),
             valB = TableSort.getCellValue(b, index);
         return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
      };
   },

   getCellValue: function(row, index) {
      return $(row).children('td').eq(index).text();
   }
};


var Shell = {

   urlRoot: "/index.php",
   arAvisoInfo: [],
   arAvisoErro: [],
   isVisibleFilter: true,

   showModal: {
      backdrop: "static",
      keyboard: false
   },

   datePickerLocale: {
      "format": "DD/MM/YYYY",
      "separator": " - ",
      "applyLabel": "Usar",
      "cancelLabel": "Cancelar",
      "fromLabel": "De",
      "toLabel": "Até",
      "customRangeLabel": "Custom",
      "weekLabel": "SEM",
      "daysOfWeek": [
         "Dom",
         "Seg",
         "Ter",
         "Qua",
         "Qui",
         "Sex",
         "Sab"
      ],
      "monthNames": [
         "Janeiro",
         "Fevereiro",
         "Março",
         "Abril",
         "Maio",
         "Junho",
         "Julho",
         "Agosto",
         "Setembro",
         "Outubro",
         "Novembro",
         "Dezembro"
      ],
      "firstDay": 1
    },

   dataTableLanguage: {
      "decimal": ",",
      "thousands": ".",
      "search": "Filtrar:",
      "lengthMenu": "_MENU_ registros/página",
      "zeroRecords": "Nenhuma ocorrência encontrada",
      "info": "_PAGE_ / _PAGES_",
      "infoEmpty": "Sem Registros",
      "infoFiltered": "(total: _MAX_)"
   },

   // http://bootstrap-datepicker.readthedocs.io/en/latest/
   setDatePicker: function() {
      $('.datepicker').datepicker({
         format: 'dd/mm/yyyy',
         language: 'pt-BR',
         weekStart: 0,
         todayHighlight: true,
         autoclose: true
      });
   },

   setMonthPicker: function() {
      $('.monthpicker').datepicker({
         format: 'mm/yyyy',
         language: 'pt-BR',
         viewMode: "months",
         minViewMode: "months",
         autoclose: true
      });
   },

   setYearPicker: function() {
      $('.yearpicker').datepicker({
         format: 'yyyy',
         language: 'pt-BR',
         viewMode: "years",
         minViewMode: "years",
         autoclose: true
      });
   },

   // http://www.daterangepicker.com/
   setDateRangePicker: function() {
      $('.daterangepicker').daterangepicker({
         "autoApply": true,
         "opens": "right",
         "locale": Shell.datePickerLocale
      });
   },

   // http://jonthornton.github.com/jquery-timepicker/
   setTimePicker: function() {
      $('.timepicker').timepicker({
         closeOnWindowScroll: false,
         scrollDefault: 'now',
         minTime: '00:00',
         maxTime: '23:59',
         orientation: 'lb',
         timeFormat: 'H:i'
      });
   },

   // https://farbelous.io/bootstrap-colorpicker/v2/
   setColorPicker: function() {
      $('.colorpicker-element').colorpicker({
         format: 'hex',
         colorSelectors: {
            '#000000': '#000000',
            '#ffffff': '#ffffff',
            '#FF0000': '#FF0000',
            '#777777': '#777777',
            '#337ab7': '#337ab7',
            '#5cb85c': '#5cb85c',
            '#5bc0de': '#5bc0de',
            '#f0ad4e': '#f0ad4e',
            '#d9534f': '#d9534f'
         }
      });
   },

   getUrlParam: function() {
      var obj = {};
      var paramList = window.location.search.substr(1).split("&");
      for(var i=0, j=paramList.length; i<j; i++) {
         var param = paramList[i].split("=");
         obj[param[0]] = param[1];
      }
      return obj;
   },

   windowUrl: function(a) {
      window.event.stopPropagation();
      window.event.preventDefault();
      window.open(Shell.urlRoot + "?acao=" + a, '_blank');
   },

   editingWarning: function() {
      $(window).bind('beforeunload', function() {
         return "Gostaria mesmo de sair desta página?";
      });
   },

   disableEditingWarning: function() {
      $(window).unbind('beforeunload');
   },

   addHiddenField: function(form, name, value) {
      form = $(form);
      var elem = $('[name="'+name+'"]').eq(0);
      if(elem.length) {
         elem.val(value);
      } else {
         $('<input>').attr({
            type: 'hidden',
            name: name,
            value: value
         }).appendTo(form);
      }
   },

   enableForm: function() {
      $('*:disabled').prop('disabled', false);
   },

   disableForm: function() {
      $('*:disabled').prop('disabled', true);
   },

   disabledTabClick: function() {
      $('.tab').click(function(event){
         if($(this).hasClass('disabled')) return false;
      });
   },

   serializeJSON: function(form) {
      var o = {};
      var a = form.serializeArray();
      $.each(a, function() {
         if (o[this.name]) {
            if (!o[this.name].push) {
               o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
         } else {
            o[this.name] = this.value || '';
         }
      });
      return o;
   },

   showAlert: function(type, message) {
      var alertBox = null;
      switch(type) {
         case "danger":  alertBox = $("#alert-message-danger");  break;
         case "success": alertBox = $("#alert-message-success"); break;
         case "info":    alertBox = $("#alert-message-info");    break;
         case "warning": alertBox = $("#alert-message-warning"); break;
      }
      alertBox.find(".message").html(message);
      alertBox.show();
   },

   sizeFilter: function() {
      var obj = $('#boxFiltro'),
          box = obj.find('.box-info'),
          head = obj.find('.box-header'),
          body = obj.find('.box-body'),
          foot = obj.find('.box-footer');
      if(Shell.isVisibleFilter) {
         body.hide();
         foot.hide();
         box.css({height: "40px"});
         obj.removeClass('col-sm-3').addClass('col-sm-1');
         Shell.isVisibleFilter = false;
      } else {
         body.show();
         foot.show();
         box.css({height: "auto"});
         obj.removeClass('col-sm-1').addClass('col-sm-3');
         Shell.isVisibleFilter = true;
      }
   },

   isEmail: function(email) {
     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,6})+$/;
     return regex.test(email);
   },

   addAvisoInfo: function(message) {
      var idx = $.inArray(message, Shell.arAvisoInfo);
      if(idx == -1) {
         Shell.arAvisoInfo.push(message);
         idx = $.inArray(message, Shell.arAvisoInfo);
         $('<li>').addClass('index_'+idx).html(message).appendTo($('#box-info .x-infos'));
      }
      return idx;
   },

   addAvisoErro: function(message) {
      var idx = $.inArray(message, Shell.arAvisoErro);
      if(idx == -1) {
         Shell.arAvisoErro.push(message);
         idx = $.inArray(message, Shell.arAvisoErro);
         $('<li>').addClass('index_'+idx).html(message).appendTo($('#box-error .x-errors'));
      }
      return idx;
   },

   showAvisoInfo: function() {
      if($('#box-info .x-infos').has('li').length > 0) $('#box-info').show();
      window.scrollTo(0, 0);
   },

   showAvisoErro: function() {
      if($('#box-error .x-errors').has('li').length > 0) $('#box-error').show();
      window.scrollTo(0, 0);
   },

   showAvisos: function() {
      Shell.showAvisoInfo();
      Shell.showAvisoErro();
   },

   removeAvisoErro: function(message) {
      Shell.removeAvisoErroByIndex($.inArray(message, Shell.arAvisoErro));
   },

   removeAvisoErroByIndex: function(index) {
      if (index >= 0) {
         Shell.arAvisoErro.splice(index, 1);
         $('#box-error .x-errors').find('.index_'+index).remove();
      }
   },

   clearArrayAvisos: function() {
      Shell.arAvisoInfo.length = 0;
      Shell.arAvisoErro.length = 0;
   },

   clearAvisos: function() {
      Shell.clearArrayAvisos();
      $('#box-info .x-infos').empty();
      $('#box-error .x-errors').empty();
      $('#box-info').hide();
      $('#box-error').hide();
   },
   mask: {
      cnpj: "99.999.999/9999-99",
      cpf: "999.999.999-99",
      cep: "99.999-999",
      fone1: "(99) 9999-9999",
      fone2: "(99) 99999-9999"
   },
   applyMask: function(selector, type, mask) {
      $(selector).each(function(idx, obj){
         if(type == 'text') {
            var val = obj.innerText;
            if(val != '') $(this).text(Inputmask.format(val, {alias:mask}));
         } else {
            var val = obj.value;
            if(val != '') $(this).val(Inputmask.format(val, {alias:mask}));
         }
      });
   },

   setInputMask: function() {
      $(":input").inputmask();
   },

   clearMask: function() {
      $("input[data-inputmask").each(function(idx, elem) {
         $(elem).inputmask('unmaskedvalue');
      });
   },

   setNumericInputMask: function() {
      $(":input.numeric").inputmask({
         mask: "[999.99]9,99",
         numericInput: true,
         rightAlign: true,
         radixPoint: 2,
         clearMaskOnLostFocus: true
      });
   },

   setPercentInputMask: function() {
      $(":input.percent").inputmask({
         'alias': 'decimal',
         'groupSeparator': '.',
         'autoGroup': true,
         radixPoint: ",",
         'digits': 2,
         'digitsOptional': false,
         'placeholder': '0,00',
         rightAlign : true,
         clearMaskOnLostFocus: !1
      });
   },

   setNumeroInputMask: function() {
      $(":input.numero").inputmask({
         mask: "9{1,10}",
         numericInput: true,
         rightAlign: true,
         clearMaskOnLostFocus: true
      });
   },

   setCepInputMask: function() {
      $(":input.cep").inputmask({
         mask: Shell.mask.cep,
         clearMaskOnLostFocus: true
      });
   },

   setCnpjInputMask: function() {
      $(":input.cnpj").inputmask({
         mask: Shell.mask.cnpj,
         clearMaskOnLostFocus: true
      });
   },

   setCpfInputMask: function() {
      $(":input.cpf").inputmask({
         mask: Shell.mask.cpf,
         clearMaskOnLostFocus: true
      });
   },

   setFoneInputMask: function() {
      $(":input.fone").inputmask({
         mask: [Shell.mask.fone1, Shell.mask.fone2],
         clearMaskOnLostFocus: true
      });
   },
   stringToFloat: function(value) {
      if(value == undefined) return 0;
      value = value.replace(/[^0-9\,]+/g, "");
      value = value.replace(/\,+/g, ".");
      value = Number(value).toFixed(2).toLocaleString();
      return Number(value);
   },

   floatToString: function(value, precision) {
      var fix = (!isNaN(precision)) ? parseInt(precision) : 2;
      value = value.toFixed(fix);
      return value.toString().replace(".", ",");
   },

   numberToString: function(value) {
      return accounting.formatMoney(value, "", 2, ".", ",");
   },

   pad: function(str, max) {
      return str.length < max ? this.pad("0" + str, max) : str;
   },

};
