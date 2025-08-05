<script>
  function isNumber(evt) {
   evt = (evt) ? evt : window.event;
   var charCode = (evt.which) ? evt.which : evt.keyCode;
   if (charCode > 31 && (charCode < 48 || charCode > 57)) {
       return false;
   }
   return true;
}


$('.loglog').on('click', function() {
        Swal.fire({
            title: 'Are you sure to logout ?',
            type: 'warning',
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#30347a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.value === true) {
                $('#logout-form').submit()
            }
        })
    })


    $(document).on("click", ".delete-link", function (e) {
    e.preventDefault(); 
    const url = $(this).attr("href"); 

    // Gunakan SweetAlert versi terbaru
    Swal.fire({
        title: "Yakin akan menghapus data ini?",
        text: "Data yang dihapus tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Kirim permintaan AJAX jika dikonfirmasi
            return $.ajax({
                url: url,
                method: "GET", // Atau sesuaikan dengan metode API Anda (GET, POST, DELETE)
            })
                .then((response) => {
                    // Tangani respons sukses
                    if (response.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.message || "Data berhasil dihapus.",
                            icon: "success",
                        }).then(() => {
                            location.reload(); // Reload halaman setelah sukses
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal!",
                            text: response.message || "Terjadi kesalahan saat menghapus data.",
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    // Tangani error
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan pada server.",
                        icon: "error",
                    });
                });
        },
        allowOutsideClick: () => !Swal.isLoading(),
    });
});

  $(document).on('click', '.btn-sweet-delete', function (e) {
      e.preventDefault();

      // Ambil semua checkbox yang dicentang
      let selectedIds = [];
      $('input[name="id[]"]:checked').each(function () {
          selectedIds.push($(this).val());
      });

      // Jika tidak ada data yang dipilih, tampilkan peringatan
      if (selectedIds.length === 0) {
          Swal.fire({
              title: 'Tidak ada data yang dipilih',
              icon: 'warning',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'OK',
          });
          return;
      }

      // SweetAlert untuk konfirmasi penghapusan
      Swal.fire({
          title: 'Yakin akan menghapus data yang dipilih?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
      }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu beberapa saat.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                },
            });

            // Submit form setelah loading ditampilkan
            $('#delete-form').submit();
          }
      });
  });

  // Fungsi untuk toggle checkbox "Pilih Semua"
  $('#select-all').on('change', function () {
      $('input[name="id[]"]').prop('checked', $(this).prop('checked'));
  });

</script>


<script>
tinymce.init({
  selector: '.simple',
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>

<?php 
$sek  = date('Y');
$awal = $sek-5;
?>

<script>
   $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    
  $( ".datepicker" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+5; echo $tahundepan; ?>"
  });

  $( ".tanggal" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "yy-mm-dd",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+5; echo $tahundepan; ?>"
  });



  $(".monthPicker").datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "MM yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+5; echo $tahundepan; ?>",
    beforeShow: function (input, inst) {
        setTimeout(function () {
            inst.dpDiv.find('.ui-datepicker-calendar').hide();
        }, 0);
    },
    showButtonPanel: true,
    onClose: function (dateText, inst) {
        var selectedMonth = inst.selectedMonth + 1; // Bulan dimulai dari 0 (Januari)
        var selectedYear = inst.selectedYear;
        $(this).val($.datepicker.formatDate('MM yy', new Date(selectedYear, selectedMonth - 1, 1)));
    }
  });

  $( ".tanggalan" ).datepicker({
    inline: true,
    changeYear: true,
    changeMonth: true,
    dateFormat: "dd-mm-yy",
    yearRange: "<?php echo $awal ?>:<?php $tahundepan = date('Y')+5; echo $tahundepan; ?>"
  });

</script>

<script>

      @if ($message = Session::get('sukses'))
        Swal.fire({
            title: "Berhasil",
            text: "{{ $message }}", 
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
        });
    @endif

    @if ($message = Session::get('warning'))
        Swal.fire({
            title: "Oops..",
            text: "{{ $message }}", 
            icon: "warning",
            confirmButtonColor: '#ff8c00',
            timer: 2000,
            showConfirmButton: false,
        });
    @endif

    @if ($message = Session::get('error'))
        Swal.fire({
            title: "Error",
            text: "{{ $message }}",
            icon: "error",
            timer: 2000,
            showConfirmButton: false,
        });
    @endif

</script>



</div>
</div>

</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>

{{-- <footer class="main-footer">
  <strong><i class="fas fa-copyright"></i>
    <a href="javascript:void(0)"> TS3 Indonesia <?= date('Y'); ?> </a> 
  </strong>


</footer> --}}

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/admin/plugins/sparklines/sparkline.js') }}"></script>
>
<script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- tinymce -->
  <script src="{{ asset('assets/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<!-- pace-progress -->
<script src="{{ asset('assets/admin/plugins/pace-progress/pace.min.js') }}"></script>
<script src="{{ asset('assets/admin/dist/js/adminlte.js') }}"></script>

  

<script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();

    $("#spklist").DataTable({
      "order": [6, 'asc'],
    });

    $("#example3").DataTable();
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });

    $('#example4').DataTable({
      "paging": true,
      "lengthChange": false,
      "scrollCollapse": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      
    });
  });

  </script>

</body>
</html>