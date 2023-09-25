$(document).ready(function () {
  $("#custom-validation").validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
        minlength: 5,
      },
      namaSumberDana: {
        required: true,
        maxlength: 255,
      },
      namaSarana: {
        required: true,
        maxlength: 255,
      },
      namaLantai: {
        required: true,
        maxlength: 255,
      },
      namaGedung: {
        required: true,
        maxlength: 255,
      },
      namaPrasarana: {
        required: true,
        maxlength: 255,
      },
      luas: {
        required: true,
        maxlength: 255,
      },
      namaStatusLayanan: {
        required: true,
        maxlength: 255,
      },
      idIdentitasLantai: {
        required: true,
      },
      idIdentitasGedung: {
        required: true,
      },
    },
    messages: {
      username: {
        required: "Silahkan masukkan username",
      },
      password: {
        required: "Silahkan masukkan kata sandi",
        minlength: "Kata sandi harus terdiri dari setidaknya 5 karakter",
      },
      namaSumberDana: {
        required: "Silahkan masukkan nama sumber dana",
        maxlength: "Nama sumber dana tidak boleh melebihi 255 karakter",
      },
      namaSarana: {
        required: "Silahkan masukkan nama sarana",
        maxlength: "Nama sarana tidak boleh melebihi 255 karakter",
      },
      namaLantai: {
        required: "Silahkan masukkan nama lantai",
        maxlength: "Nama lantai tidak boleh melebihi 255 karakter",
      },
      namaGedung: {
        required: "Silahkan masukkan nama gedung",
        maxlength: "Nama gedung tidak boleh melebihi 255 karakter",
      },
      namaPrasarana: {
        required: "Silahkan masukkan nama prasarana",
        maxlength: "Nama prasarana tidak boleh melebihi 255 karakter",
      },
      luas: {
        required: "Silahkan masukkan luas",
        maxlength: "Luas tidak boleh melebihi 255 karakter",
      },
      namaStatusLayanan: {
        required: "Silahkan masukkan nama status layanan",
        maxlength: "Nama status layanan tidak boleh melebihi 255 karakter",
      },
      idIdentitasLantai: {
        required: "Silahkan pilih sebuah opsi",
      },
      idIdentitasGedung: {
        required: "Silahkan pilih sebuah opsi",
      },

    },
    errorPlacement: function (label, element) {
      label.addClass("mt-1 tx-13 text-danger");
      label.insertAfter(element);
    },
    highlight: function (element, errorClass) {
      $(element).parent().addClass("validation-error");
      $(element).addClass("border-danger");
    },
    unhighlight: function (element, errorClass) {
      $(element).parent().removeClass("validation-error");
      $(element).removeClass("border-danger");
      $(element).addClass("border-success");
    },
  });
});
