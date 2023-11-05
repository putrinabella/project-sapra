$(function() {
  'use strict';

  $(function() {
    var dataTable = $('#dataTable');

    var tableWidth = dataTable.width();

    var scrollXThreshold = 1160; 

    var dataTableOptions = {
      "aLengthMenu": [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "All"]
      ],
      "iDisplayLength": 10,
      "search": {
        "smart": false
      },
      "language": {
        search: ""
      }
    };

    if (tableWidth > scrollXThreshold) {
      dataTableOptions.scrollX = true;
    }

    dataTable.DataTable(dataTableOptions);

    dataTable.each(function() {
      var searchInput = dataTable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      searchInput.attr('placeholder', 'Search');
      searchInput.removeClass('form-control-sm');
      var lengthSel = dataTable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      lengthSel.removeClass('form-control-sm');
    });
  });
});
