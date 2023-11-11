// $(function() {
//   'use strict';

//   $(function() {
//     var dataTable = $('#dataTable');

//     var tableWidth = dataTable.width();

//     var scrollXThreshold = 1160; 

//     var dataTableOptions = {
//       "aLengthMenu": [
//         [5, 10, 25, 50, 100, -1],
//         [5, 10, 25, 50, 100, "All"]
//       ],
//       "iDisplayLength": 10,
//       "search": {
//         "smart": false
//       },
//       "language": {
//         search: ""
//       }
//     };

//     if (tableWidth > scrollXThreshold) {
//       dataTableOptions.scrollX = true;
//     }

//     dataTable.DataTable(dataTableOptions);

//     dataTable.each(function() {
//       var searchInput = dataTable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
//       searchInput.attr('placeholder', 'Search');
//       searchInput.removeClass('form-control-sm');
//       var lengthSel = dataTable.closest('.dataTables_wrapper').find('div[id$=_length] select');
//       lengthSel.removeClass('form-control-sm');
//     });
//   });
// });

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