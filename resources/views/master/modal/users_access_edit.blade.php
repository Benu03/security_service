<div class="modal fade" id="modalEditUserAccess" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 70%; width: auto;">
    <form id="formEditUserAccess">
        @csrf
      <div class="modal-content">
        <div class="modal-header" style="background-color: rgba(226, 106, 8, 0.85); color: white;">
          <h5 class="modal-title"><i class="fas fa-pen"></i> Edit User Access</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-row">
            
            <!-- Username -->
            <div class="form-group col-md-6">
              <label for="editUsername">Username</label>
              <input type="text" class="form-control" id="editUsername" name="username" readonly>
            </div>

            <!-- NIK -->
            <div class="form-group col-md-6">
              <label for="editNik">NIK</label>
              <input type="text" class="form-control" id="editNik" name="nik" readonly>
            </div>

            <!-- Fullname -->
            <div class="form-group col-md-6">
              <label for="editFullname">Full Name</label>
              <input type="text" class="form-control" id="editFullname" name="fullname" readonly>
            </div>

            <!-- Role -->
            <div class="form-group col-md-6">
              <label for="editRole">Role</label>
              <input type="text" class="form-control" id="editRole" name="role" readonly>
            </div>


            <div class="form-group col-md-4">
              <label for="editCustomer">Customer</label>
              <select class="form-control select2" id="editCustomer" name="customer" style="width: 100%;">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $customer)
                  <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                @endforeach
              </select>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" id="editId" name="id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">💾 Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
  $(document).on('click', '.btn-edit', function() {
      let rowData = $('#dataTableUserAccess').DataTable().row($(this).parents('tr')).data();

      // isi field modal
      $('#editId').val(rowData.id);
      $('#editUsername').val(rowData.username);
      $('#editNik').val(rowData.nik);
      $('#editFullname').val(rowData.fullname);
      $('#editRole').val(rowData.role);
      $('#editCreatedDate').val(rowData.created_date);

      // set customer di select2
      $('#editCustomer').val(rowData.customer).trigger('change');

      // tampilkan modal
      $('#modalEditUserAccess').modal('show');
  });

  // aktifkan select2
  $(document).ready(function() {
      $('#editCustomer').select2({
          dropdownParent: $('#modalEditUserAccess')
      });
  });

</script>

<script>
  $('#formEditUserAccess').on('submit', function(e) {
    e.preventDefault();



    // Lanjut AJAX submit
    Swal.fire({
        title: 'Memperbaharui data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: "{{ route('edit-users-access') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            Swal.close();
            if(res.success){
               
                    // Reset form
                    $('#formEditUserAccess')[0].reset(); 
                    $('#modalEditUserAccess').modal('hide');
                    // Reload DataTable
                    $('#dataTableUserAccess').DataTable().ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil diperbaharui', timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Terjadi kesalahan' });
            }
        },
        error: function(xhr){
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server' });
        }
    });

  });


</script>

