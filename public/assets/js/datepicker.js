$(function() {
  'use strict';
  function initializeDatepicker(selector) {
    var date = new Date();
    // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    
    $(selector).datepicker({
      format: "yyyy-mm-dd",
      todayHighlight: true,
      autoclose: true
    });

    // $(selector).datepicker('setDate', today);
  }

  if ($('#tanggal').length) {
    initializeDatepicker('#tanggal');
  }
  if ($('#startDate').length) {
    initializeDatepicker('#startDate');
  }
  if ($('#endDate').length) {
    initializeDatepicker('#endDate');
  }

  if ($('#tanggalSkPendirian').length) {
    initializeDatepicker('#tanggalSkPendirian');
  }

  if ($('#tanggalSkIzinOperasional').length) {
    initializeDatepicker('#tanggalSkIzinOperasional');
  }
});
