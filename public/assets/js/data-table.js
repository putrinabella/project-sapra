$(function() {
  'use strict';

  $(function() {
    $('#dataTable').DataTable({
      "aLengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      "iDisplayLength": 10,
      "search": {
        "smart": false
      },
      "language": {
        search: ""
      },
      "scrollX": true 
    });
    $('#dataTable').each(function() {
      var datatable = $(this);
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
});

$(function() {
  'use strict';

  $(function() {
    $('#anotherDataTable').DataTable({
      "aLengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      "iDisplayLength": 10,
      "search": {
        "smart": false
      },
      "language": {
        search: ""
      },
      "scrollX": true 
    });
    $('#anotherDataTable').each(function() {
      var datatable = $(this);
      var search_input = datatable.closest('.anotherDataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      var length_sel = datatable.closest('.anotherDataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
});