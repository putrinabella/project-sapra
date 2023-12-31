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
        "search": "",
        "lengthMenu": 'Show _MENU_ entries' 
      },
      "scrollX": true,
        responsive: true
    });
    $('#dataTable').each(function() {
      var datatable = $(this);
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      // search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      var searchLabel = $('<label for="' + search_input.attr('id') + '">Search</label>');
      searchLabel.css('padding-left', '8px');
      search_input.before(searchLabel);    
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
});

$(function() {
  'use strict';

  $(function() {
    $('#anotherTable').DataTable({
      "aLengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      "iDisplayLength": 10,
      "search": {
        "smart": false
      },
      "language": {
        "search": "",
        "lengthMenu": 'Show _MENU_ entries' 
      },
      "scrollX": true,
        responsive: true
    });
    $('#anotherTable').each(function() {
      var datatable = $(this);
      var search_input = datatable.closest('.anotherTables_wrapper').find('div[id$=_filter] input');
      // search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      var searchLabel = $('<label for="' + search_input.attr('id') + '">Search</label>');
      searchLabel.css('padding-left', '8px');
      search_input.before(searchLabel);    
      var length_sel = datatable.closest('.anotherTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
});
