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
      tahunPengadaan: {
        required: true,
        minlength: 4,
        maxlength: 4,
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
      namaKategoriManajemen: {
        required: true,
        maxlength: 255,
      },
      saranaLayak: {
        required: true,
        maxlength: 4,
      },
      saranaRusak: {
        required: true,
        maxlength: 4,
      },
      spesifikasi: {
        required: true,
      },
      link: {
        required: true,
      },
      idIdentitasLantai: {
        required: true,
      },
      idIdentitasGedung: {
        required: true,
      },
      idIdentitasSarana: {
        required: true,
      },
      idSumberDana: {
        required: true,
      },
      idKategoriManajemen: {
        required: true,
      },
      kodePrasarana: {
        required: true,
      },
      formExcel: {
        required: true,
      },  
      idIdentitasPrasarana: {
        required: true,
      },  
      idStatusLayanan: {
        required: true,
      },  
      biaya: {
        required: true,
        minlength: 4,
      },  
      tanggal: {
        required: true,
      },  
      namaWebsite: {
        required: true,
      }, 
      fungsiWebsite: {
        required: true,
      }, 
      linkWebsite: {
        required: true,
      }, 
      picWebsite: {
        required: true,
      }, 
      namaSosialMedia: {
        required: true,
      }, 
      usernameSosialMedia: {
        required: true,
      }, 
      linkSosialMedia: {
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
      tahunPengadaan: {
        required: "Silahkan masukkan tahun pengadaan",
        minlength: "Tahun pengadaan minimal terdiri dari 4 karakter",
        maxlength: "Tahun pengadaan maksimal terdiri dari 4 karakter",
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
      namaKategoriManajemen: {
        required: "Silahkan masukkan nama kategori manajemen",
        maxlength: "Nama kategori manajemen tidak boleh melebihi 255 karakter",
      },
      saranaLayak: {
        required: "Silahkan masukkan jumlah sarana layak",
        maxlength: "Angka yang dimasukkan tidak boleh melebihi 4 karakter",
      },
      saranaRusak: {
        required: "Silahkan masukkan jumlah sarana rusak",
        maxlength: "Angka yang dimasukkan tidak boleh melebihi 4 karakter",
      },
      spesifikasi: {
        required: "Silahkan masukkan spesifikasi",
      },
      link: {
        required: "Silahkan masukkan link dokumentasi",
      },
      idIdentitasLantai: {
        required: "Silahkan pilih sebuah opsi",
      },
      idIdentitasGedung: {
        required: "Silahkan pilih sebuah opsi",
      },
      idIdentitasSarana: {
        required: "Silahkan pilih sebuah opsi",
      },
      idSumberDana: {
        required: "Silahkan pilih sebuah opsi",
      },
      idKategoriManajemen: {
        required: "Silahkan pilih sebuah opsi",
      },
      kodePrasarana: {
        required: "Silahkan pilih sebuah opsi",
      },
      formExcel: {
        required: "Silahkan masukkan sebuah file",
      },
      idIdentitasPrasarana: {
        required: "Silahkan pilih sebuah opsi",
      },
      idStatusLayanan: {
        required: "Silahkan pilih sebuah opsi",
      },
      biaya: {
        required: "Silahkan masukkan biaya",
        minlength: "Biaya minimal terdiri dari 4 digit",
      },
      tanggal: {
        required: "Silahkan masukkan tanggal",
      },
      namaWebsite: {
        required: "Silahkan masukkan nama website",
      },
      fungsiWebsite: {
        required: "Silahkan masukkan fungsi website",
      },
      linkWebsite: {
        required: "Silahkan masukkan link website",
      },
      picWebsite: {
        required: "Silahkan masukkan PIC website",
      },
      namaSosialMedia: {
        required: "Silahkan masukkan jenis sosial media",
      },
      usernameSosialMedia: {
        required: "Silahkan masukkan username sosial media",
      },
      linkSosialMedia: {
        required: "Silahkan masukkan link sosial media",
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
