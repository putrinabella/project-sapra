$(function() {
  'use strict';

  if($('#tanggal').length) {
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#tanggal').datepicker({
      format: "yyyy-mm-dd",
      todayHighlight: true,
      autoclose: true
    });    
    $('#tanggal').datepicker('setDate', today);
  }
});