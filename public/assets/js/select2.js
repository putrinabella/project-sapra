$(function() {
  'use strict';

  if ($(".js-example-basic-single").length) {
    $(".js-example-basic-single").select2();
  }

  if ($(".js-example-basic-multiple").length) {
    $(".js-example-basic-multiple").select2();
  }
  
  $('#modalImport').on('shown.bs.modal', function() {
    $('.myselect2').select2({
      allowClear: true,
      width: '100%',
      dropdownParent: $('#modalImport'), 
      
    });    
  });
});
