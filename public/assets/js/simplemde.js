document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var spesifikasiElement = document.getElementById('spesifikasi');

  if (spesifikasiElement) {
      var simplemde = new SimpleMDE({
          element: spesifikasiElement
      });
  }
});
