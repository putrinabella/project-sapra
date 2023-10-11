// $(function() {
//   'use strict';

//   if($('#tanggal').length) {
//     var date = new Date();
//     var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
//     $('#tanggal').datepicker({
//       format: "yyyy-mm-dd",
//       todayHighlight: true,
//       autoclose: true
//     });    
//     $('#tanggal').datepicker('setDate', today);
//   }
// });
$(function() {
  'use strict';

  // Function to initialize datepicker for a given element
  function initializeDatepicker(selector) {
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    
    $(selector).datepicker({
      format: "yyyy-mm-dd",
      todayHighlight: true,
      autoclose: true
    });

    $(selector).datepicker('setDate', today);
  }

  // Check if #tanggal exists and initialize datepicker
  if ($('#tanggal').length) {
    initializeDatepicker('#tanggal');
  }

  // Check if #tanggalSkPendirian exists and initialize datepicker
  if ($('#tanggalSkPendirian').length) {
    initializeDatepicker('#tanggalSkPendirian');
  }

  // Check if #tanggalSkIzinOperasional exists and initialize datepicker
  if ($('#tanggalSkIzinOperasional').length) {
    initializeDatepicker('#tanggalSkIzinOperasional');
  }
});
