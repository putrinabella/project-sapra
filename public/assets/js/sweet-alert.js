// document.querySelectorAll('[data-confirm]').forEach(function(button) {
//     button.addEventListener('click', function(e) {
//         e.preventDefault();

//         var confirmationMessage = button.getAttribute('data-confirm');

//          Swal.fire({
//             title: 'Hapus data',
//             text: confirmationMessage,
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonClass: 'me-2',
//             confirmButtonText: 'Ya',
//             cancelButtonText: 'Tidak',
//             reverseButtons: true,
//         }).then(function(result) {
//             if (result.isConfirmed) {
//                 var formId = button.closest('form').id;
//                 document.getElementById(formId).submit();
//                 Swal.fire(
//                     'Berhasil!',
//                     'Data sudah terhapus',
//                     'success'
//                 );
//             } else if (result.dismiss === Swal.DismissReason.cancel) {
//                 Swal.fire(
//                     'Dibatalkan',
//                     'Data batal dihapus',
//                     'error'
//                 );
//             }
//         });
//     });
// });

document.querySelectorAll('[data-confirm]').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        var confirmationMessage = button.getAttribute('data-confirm');
        var title = button.getAttribute('data-title'); // Add this line to get the title

        Swal.fire({
            title: title, // Set the title from the data-title attribute
            text: confirmationMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'me-2',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
        }).then(function(result) {
            if (result.isConfirmed) {
                var formId = button.closest('form').id;
                document.getElementById(formId).submit();
                Swal.fire(
                    'Berhasil!',
                    'Data sudah terhapus',
                    'success'
                );
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Dibatalkan',
                    'Data batal dihapus',
                    'error'
                );
            }
        });
    });
});
