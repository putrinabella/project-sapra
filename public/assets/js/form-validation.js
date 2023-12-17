$(document).ready(function () {
  $.validator.addMethod("googleDriveLink", function(value, element) {
    var pattern = /^https:\/\/drive\.google\.com\/file\/d\/[a-zA-Z0-9_-]+\/view/;

    return this.optional(element) || pattern.test(value);
  }, "Input harus berupa Google Drive Link");

  $.validator.addMethod("passwordMatch", function(value, element) {
    var password = $("#password").val();
    var konfirmasiPassword = value;

    return password === konfirmasiPassword;
  }, "Password harus sama dengan Konfirmasi Password");

  $.validator.addMethod("validDate", function(value, element) {
    return !/Invalid|NaN/.test(new Date(value).toString());
  }, "Tanggal tidak valid");
  $("#custom-validation").validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
        minlength: 5,
      },
      konfirmasiPassword: {
        required: true,
        equalTo: "#password", 
      },
      tahunPengadaan: {
        required: true,
        minlength: 4,
        maxlength: 4,
      },
      tahunPemakaianAir: {
        required: true,
        minlength: 4,
        maxlength: 4,
      },
      namaSumberDana: {
        required: true,
        maxlength: 255,
      },
      kodeSumberDana: {
        required: true,
        maxlength: 255,
      },
      namaSarana: {
        required: true,
        maxlength: 255,
      },
      kodeSarana: {
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
      namaLab: {
        required: true,
        maxlength: 255,
      },
      luas: {
        required: true,
        maxlength: 255,
      },
      nomorBarang: {
        required: true,
        maxlength: 255,
      },
      namaStatusLayanan: {
        required: true,
        maxlength: 255,
      },
      kodeKategoriManajemen: {
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
      kodeRincianLabAset: {
        required: true,
      },
      kodeRincianAset: {
        required: true,
      },
      idIdentitasLantai: {
        required: true,
      },
      bulanPemakaianAir: {
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
      kodeLab: {
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
        validDate: true,
      },  
      namaWebsite: {
        required: true,
      }, 
      namaAplikasi: {
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
      picSosialMedia: {
        required: true,
      }, 
      picAplikasi: {
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
      npsn: {
        required: true,
        minlength: 8,
        maxlength: 15,
      }, 
      bentukPendidikan: {
        required: true,
      }, 
      status: {
        required: true,
      }, 
      statusKepemilikan: {
        required: true,
      }, 
      skPendirian: {
        required: true,
      }, 
      skIzinOperasional: {
        required: true,
      }, 
      statusBos: {
        required: true,
      }, 
      sertifikasiIso: {
        required: true,
      }, 
      sumberListrik: {
        required: true,
      }, 
      kecepatanInternet: {
        required: true,
      }, 
      siswaKebutuhanKhusus: {
        required: true,
      }, 
      namaBank: {
        required: true,
      }, 
      cabangKcp: {
        required: true,
      }, 
      atasNamaRekening: {
        required: true,
      }, 
      tanggalSkPendirian: {
        required: true,
      }, 
      tanggalSkIzinOperasional: {
        required: true,
      }, 
      kepsek: {
        required: true,
      }, 
      operator: {
        required: true,
      }, 
      akreditasi: {
        required: true,
      }, 
      kurikulum: {
        required: true,
      }, 
      namaPeminjam2: {
        required: true,
      }, 
      asalPeminjam: {
        required: true,
      }, 
      jumlah: {
        required: true,
      }, 
      tanggalPengembalian: {
        required: true,
      }, 
      tipe: {
        required: true,
      }, 
      hargaBeli: {
        required: true,
      }, 
      warna: {
        required: true,
      }, 
      merk: {
        required: true,
      }, 
      noSeri: {
        required: true,
      }, 
      type: {
        required: true,
      }, 
      bukti: {
        required: true,
        googleDriveLink: true, 
      },
      isiPengaduan: {
        required: true,
      }, 
      isiFeedback: {
        required: true,
      }, 
      keterangan: {
        required: true,
      }, 
      kategoriPeminjam: {
        required: true,
      }, 
      namaPeminjam: {
        required: true,
      }, 
      kelasJabatan: {
        required: true,
      }, 
      keperluanAlat: {
        required: true,
      }, 
      lamaPinjam: {
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
      konfirmasiPassword: {
        required: "Silahkan masukkan konfirmasi password",
        equalTo: "Password harus sama dengan Konfirmasi Password",
      },
      tahunPengadaan: {
        required: "Silahkan masukkan tahun pengadaan",
        minlength: "Tahun pengadaan minimal terdiri dari 4 karakter",
        maxlength: "Tahun pengadaan maksimal terdiri dari 4 karakter",
      },
      tahunPemakaianAir: {
        required: "Silahkan masukkan tahun",
        minlength: "Tahun minimal terdiri dari 4 karakter",
        maxlength: "Tahun maksimal terdiri dari 4 karakter",
      },
      namaSumberDana: {
        required: "Silahkan masukkan nama sumber dana",
        maxlength: "Nama sumber dana tidak boleh melebihi 255 karakter",
      },
      kodeSumberDana: {
        required: "Silahkan masukkan kode sumber dana",
        maxlength: "Kode sumber dana tidak boleh melebihi 255 karakter",
      },
      namaSarana: {
        required: "Silahkan masukkan nama sarana",
        maxlength: "Nama sarana tidak boleh melebihi 255 karakter",
      },
      kodeSarana: {
        required: "Silahkan masukkan kode sarana",
        maxlength: "Kode sarana tidak boleh melebihi 255 karakter",
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
      namaLab: {
        required: "Silahkan masukkan nama prasarana",
        maxlength: "Nama prasarana tidak boleh melebihi 255 karakter",
      },
      luas: {
        required: "Silahkan masukkan luas",
        maxlength: "Luas tidak boleh melebihi 255 karakter",
      },
      nomorBarang: {
        required: "Silahkan masukkan nomor barang",
        maxlength: "Nomor barang tidak boleh melebihi 255 karakter",
      },
      namaStatusLayanan: {
        required: "Silahkan masukkan nama status layanan",
        maxlength: "Nama status layanan tidak boleh melebihi 255 karakter",
      },
      kodeKategoriManajemen: {
        required: "Silahkan masukkan kode kategori barang",
        maxlength: "Nama kategori barang tidak boleh melebihi 255 karakter",
      },
      namaKategoriManajemen: {
        required: "Silahkan masukkan nama kategori barang",
        maxlength: "Nama kategori barang tidak boleh melebihi 255 karakter",
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
      kodeRincianLabAset: {
        required: "Silahkan pilih sebuah opsi",
      },
      kodeRincianAset: {
        required: "Silahkan pilih sebuah opsi",
      },
      idIdentitasLantai: {
        required: "Silahkan pilih sebuah opsi",
      },
      bulanPemakaianAir: {
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
        required: "Silahkan masukkan kode prasarana",
      },
      kodeLab: {
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
        validDate: "Tanggal tidak valid",
      },
      tanggalPengembalian: {
        required: "Silahkan masukkan tanggal pengembalian",
      },
      namaWebsite: {
        required: "Silahkan masukkan nama website",
      },
      namaAplikasi: {
        required: "Silahkan masukkan nama aplikasi",
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
      picSosialMedia: {
        required: "Silahkan masukkan PIC sosial media",
      },
      picAplikasi: {
        required: "Silahkan masukkan PIC aplikasi",
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
      npsn: {
        required: "Silahkan masukkan NPS",
        minlength: "NPSN minimal terdiri dari 8 karakter",
        maxlength: "NPSN maksimal terdiri dari 15 karakter",
      },
      status: {
        required: "Silahkan pilih sebuah opsi",
      },
      bentukPendidikan: {
        required: "Silahkan masukkan bentuk pendidikan",
      }, 
      statusKepemilikan: {
        required: "Silahkan masukkan status kepemilikan",
      }, 
      skPendirian: {
        required: "Silahkan masukkan nomor SK pendirian",
      }, 
      skIzinOperasional: {
        required: "Silahkan masukkan nomor SK izin operasional",
      }, 
      statusBos: {
        required: "Silahkan masukkan status BOS",
      }, 
      sertifikasiIso: {
        required: "Silahkan masukkan nomor sertifikasi ISO",
      }, 
      sumberListrik: {
        required: "Silahkan masukkan sumber listrik",
      }, 
      kecepatanInternet: {
        required: "Silahkan masukkan kecepatan internet",
      }, 
      siswaKebutuhanKhusus: {
        required: "Silahkan masukkan jumlah siswa berkebutuhan khusus",
      }, 
      namaBank: {
        required: "Silahkan masukkan nama bank",
      }, 
      cabangKcp: {
        required: "Silahkan masukkan cabang KCP",
      }, 
      atasNamaRekening: {
        required: "Silahkan masukkan atas nama rekening",
      }, 
      tanggalSkPendirian: {
        required: "Silahkan masukkan tanggal",
      }, 
      tanggalSkIzinOperasional: {
        required: "Silahkan masukkan tanggal",
      }, 
      kepsek: {
        required: "Silahkan masukkan nama kepala sekolah",
      }, 
      operator: {
        required: "Silahkan masukkan nama operator",
      }, 
      akreditasi: {
        required: "Silahkan masukkan akreditasi",
      }, 
      kurikulum: {
        required: "Silahkan masukkan kurikulum",
      }, 
      namaPeminjam2: {
        required: "Silahkan masukkan nama peminjam",
      }, 
      asalPeminjam: {
        required: "Silahkan masukkan asal peminjam",
      }, 
      jumlah: {
        required: "Silahkan masukkan jumlah yang dingin dipinjam",
      }, 
      tipe: {
        required: "Silahkan pilih tipe prasarana",
      }, 
      hargaBeli: {
        required: "Silahkan masukkan harga beli",
      }, 
      warna: {
        required: "Silahkan masukkan warna",
      }, 
      merk: {
        required: "Silahkan masukkan merek",
      }, 
      noSeri: {
        required: "Silahkan masukkan nomor seri",
      }, 
      type: {
        required: "Silahkan masukkan tipe",
      }, 
      bukti: {
        required: "Silahkan masukkan Google Drive Link",
        googleDriveLink: "Input harus berupa Google Drive Link",
      },
      isiPengaduan: {
        required: "Silahkan masukkan isi pengaduan",
      }, 
      isiFeedback: {
        required: "Silahkan masukkan umpan balik",
      }, 
      keterangan: {
        required: "Silahkan masukkan keterangan",
      }, 
      kategoriPeminjam: {
        required: "Silahkan pilih sebuah opsi",
      }, 
      namaPeminjam: {
        required: "Silahkan masukkan nama peminjam",
      }, 
      kelasJabatan: {
        required: "Silahkan masukkan karyawan/kelas",
      }, 
      keperluanAlat: {
        required: "Silahkan masukkan keperluan alat",
      }, 
      lamaPinjam: {
        required: "Silahkan masukkan lama peminjaman (dalam hari)",
      }, 
    },
    errorPlacement: function (label, element) {
      // var errorContainer = element.closest('.col-sm-9');
      if (element.closest('.col-sm-9').length > 0) {
          errorContainer = element.closest('.col-sm-9');
      } else if (element.closest('.col-sm-12').length > 0) {
          errorContainer = element.closest('.col-sm-12');
      } else {
          errorContainer = element.closest('.col-sm-9');
      }
      label.addClass('error mt-1 tx-13 text-danger');
      label.appendTo(errorContainer);
      // var newRow = $("<div class='row mb-2 error-row'></div>").insertAfter(element.closest(".row"));
      // var emptyCol = $("<div class='col-sm-3'></div>");
      // var errorCol = $("<div class='col-sm-9'></div>");
      // label.addClass("error mt-1 tx-13 text-danger");
      // label.appendTo(errorCol);
      // newRow.append(emptyCol).append(errorCol);

      // label.addClass("mt-1 tx-13 text-danger");
      // label.insertAfter(element);
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
