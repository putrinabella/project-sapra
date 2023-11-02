$(function() {
  'use strict';

  function initializeYearDatepicker(selector) {
    $(selector).datepicker({
      format: "yyyy",
      todayHighlight: true,
      autoclose: true,
      minViewMode: "years" // Set the minimum view to years
    });
  }
  
  function initializeDatepicker(selector) {
    var date = new Date();
    // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    
    $(selector).datepicker({
      format: "yyyy-mm-dd",
      todayHighlight: true,
      autoclose: true
    });
  }

  if ($('#startDate').length && $('#endDate').length) {
    initializeDatepicker('#startDate');
    initializeDatepicker('#endDate');

    $('#startDate').on('changeDate', function () {
      var selectedStartDate =new Date($('#startDate').val());
      $('#endDate').datepicker('setStartDate', selectedStartDate);
    });
  }
  if ($('#tanggal').length) {
    initializeDatepicker('#tanggal');
  }
  if ($('#tanggalSkPendirian').length) {
    initializeDatepicker('#tanggalSkPendirian');
  }
  if ($('#tanggalSkIzinOperasional').length) {
    initializeDatepicker('#tanggalSkIzinOperasional');
  }
  if ($('#startYear').length && $('#endYear').length) {
    initializeYearDatepicker('#startYear');
    initializeYearDatepicker('#endYear');

    $('#startYear').on('changeDate', function () {
      var selectedStartYear = new Date($('#startYear').val()).getFullYear();
      $('#endYear').datepicker('setStartDate', new Date(selectedStartYear, 0, 1));
    });
  }
});
