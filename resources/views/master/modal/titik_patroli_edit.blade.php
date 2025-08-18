<div class="modal fade" id="modalEditTitikPatroli" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 70%; width: auto;">
    <form id="formEditTitikPatroli">
        @csrf
      <div class="modal-content">
        <div class="modal-header" style="background-color: rgba(226, 106, 8, 0.85); color: white;">
          <h5 class="modal-title"><i class="fas fa-pen"></i> Edit Titik Patroli</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-row">
            
    

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