<style>
    .custom-radio-card {
  position: relative;
  text-align: center;
  cursor: pointer;
  }

  .custom-radio-card input[type="radio"] {
    display: none;
  }

  .custom-radio-card label {
    display: block;
    padding: 8px;
    border: 2px solid #ccc;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
    background: #f8f9fa;
  }

  .custom-radio-card label i {
    font-size: 14px;
    margin-bottom: 5px;
  }

  .custom-radio-card input[type="radio"]:checked + label {
    border-color: #e26a08;
    background-color: rgba(226, 106, 8, 0.1);
    color: #e26a08;
    box-shadow: 0 0 8px rgba(226, 106, 8, 0.4);
  }

</style>


<div class="modal fade" id="modalEditTitikPatroli" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 90%; width: auto;">
    <form id="formEditTitikPatroli">
      @csrf
      <div class="modal-content">
        
        <!-- HEADER -->
        <div class="modal-header" style="background-color: rgba(226, 106, 8, 0.85); color: white;">
          <h5 class="modal-title"><i class="fas fa-pen"></i> Edit Titik Patroli</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <div class="form-row">
            <!-- Customer -->
            <div class="form-group col-md-6">
              <label for="customer"><i class="fas fa-building"></i> Customer</label>
              {{-- <select class="form-control select2" id="editcustomer" name="customer" required >
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $customer)
                  <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                @endforeach
              </select> --}}
               <input type="text" class="form-control" id="editcustomer" name="customer" readonly required>
            </div>

            <!-- Kategori -->
            <div class="form-group col-md-6">
              <label><i class="fas fa-tags"></i> Kategori</label>
              <div class="d-flex">
                <!-- Check Point -->
                <div class="custom-radio-card mr-3 flex-fill">
                  <input type="radio" id="catCheckpoint" name="Editcategory" value="CHECK POINT" required>
                  <label for="catCheckpoint">
                    <i class="fas fa-map-marker-alt"></i><br>
                    CHECK POINT
                  </label>
                </div>
                <!-- Presensi -->
                <div class="custom-radio-card flex-fill">
                  <input type="radio" id="catPresensi" name="Editcategory" value="PRESENSI">
                  <label for="catPresensi">
                    <i class="fas fa-user-check"></i><br>
                    PRESENSI
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-row">
            <!-- Input lokasi -->
            <div class="form-group col-md-6">
              <label for="location"><i class="fas fa-map"></i> Lokasi</label>
              <textarea class="form-control" id="editlocation" name="location" placeholder="Nama lokasi / deskripsi" rows="3" required></textarea>
              
              <label for="radius" class="mt-2"><i class="fas fa-ruler"></i> Radius (m)</label>
              <input type="number" class="form-control" id="editradius" name="radius" placeholder="Masukkan radius (meter)">
              
              <label for="latitude" class="mt-2"><i class="fas fa-globe"></i> Latitude</label>
              <input type="text" class="form-control" id="editlatitude" name="latitude" readonly required>
              
              <label for="longitude" class="mt-2"><i class="fas fa-globe"></i> Longitude</label>
              <input type="text" class="form-control" id="editlongitude" name="longitude" readonly required>



              <!-- Tombol Print QRCode (default hidden) -->
              <button type="button" id="btnPrintQRCode" class="btn btn-warning mt-3 d-none">
                <i class="fas fa-qrcode"></i> Print QRCode
              </button>

            </div>

            <!-- Peta -->
            <div class="form-group col-md-6">
              <label><i class="fas fa-map-marked-alt"></i> Pilih Titik pada Peta</label>
              <div id="mapEdit" style="height:400px; width:100%;"></div>

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" id="editId" name="id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
  $(document).ready(function () {
      let mapEdit, markerAdd, circleAdd;

      // Inisialisasi map edit
      mapEdit = L.map('mapEdit').setView([-6.200000, 106.816666], 13); // default Jakarta
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 18,
          attribution: '© OpenStreetMap'
      }).addTo(mapEdit);

      // Klik map untuk pilih titik
      mapEdit.on('click', function(e) {
          const { lat, lng } = e.latlng;

          if (markerAdd) markerAdd.setLatLng([lat, lng]);
          else markerAdd = L.marker([lat, lng]).addTo(mapEdit);

          $('#editlatitude').val(lat.toFixed(6));
          $('#editlongitude').val(lng.toFixed(6));

          let radius = $('#editradius').val();
          if (circleAdd) mapEdit.removeLayer(circleAdd);
          if (radius) {
              circleAdd = L.circle([lat, lng], {
                  radius: radius,
                  color: 'blue',
                  fillColor: '#3f8efc',
                  fillOpacity: 0.3
              }).addTo(mapEdit);
          }
      });

      // Update circle saat radius berubah
      $('#editradius').on('input', function() {
          let lat = $('#editlatitude').val();
          let lng = $('#editlongitude').val();
          let radius = $(this).val();

          if (lat && lng) {
              if (circleAdd) mapEdit.removeLayer(circleAdd);
              circleAdd = L.circle([lat, lng], {
                  radius: radius,
                  color: 'blue',
                  fillColor: '#3f8efc',
                  fillOpacity: 0.3
              }).addTo(mapEdit);
          }
      });

      // Edit button click
      $('#dataTabletitikPatroli').on('click', '.btn-edit', function () {
          let id = $(this).data('id');

          $.ajax({
              url: `get-titik-patroli-detail/${id}`,
              method: 'GET',
              success: function (data) {
                  const modal = $('#modalEditTitikPatroli');
                  
                  modal.find('#editId').val(data.id_titik);
                  modal.find('#editcustomer').val(data.customer_name).trigger('change');
                 modal.find(`input[name="Editcategory"][value="${data.category}"]`).prop('checked', true);
                  modal.find('#editlocation').val(data.location);
                  modal.find('#editlatitude').val(data.latitude);
                  modal.find('#editlongitude').val(data.longitude);
                  modal.find('#editradius').val(data.radius);

                  if (data.category.toUpperCase() === "CHECK POINT") {
                      $('#btnPrintQRCode')
                          .removeClass('d-none')
                          .attr('data-id', data.id_titik); // simpan id di tombol
                  } else {
                      $('#btnPrintQRCode')
                          .addClass('d-none')
                          .removeAttr('data-id'); // hapus id kalau bukan CHECK POINT
                  }
                  // Tampilkan modal
                  modal.modal('show');

 
                  if (data.latitude && data.longitude) {
                      let lat = parseFloat(data.latitude);
                      let lng = parseFloat(data.longitude);

                      if (markerAdd) markerAdd.setLatLng([lat, lng]);
                      else markerAdd = L.marker([lat, lng]).addTo(mapEdit);

                      mapEdit.setView([lat, lng], 16);

                      if (circleAdd) mapEdit.removeLayer(circleAdd);
                      if (data.radius) {
                          circleAdd = L.circle([lat, lng], {
                              radius: data.radius,
                              color: 'blue',
                              fillColor: '#3f8efc',
                              fillOpacity: 0.3
                          }).addTo(mapEdit);
                      }
                  }
              }
          });
      });

      // Reset form saat modal ditutup
      $('#modalEditTitikPatroli').on('hidden.bs.modal', function () {
          $('#formEditTitikPatroli')[0].reset();
          $('#editlatitude').val('');
          $('#editlongitude').val('');
          if (markerAdd) { mapEdit.removeLayer(markerAdd); markerAdd = null; }
          if (circleAdd) { mapEdit.removeLayer(circleAdd); circleAdd = null; }
      });

      // Fix render map saat modal muncul
      $('#modalEditTitikPatroli').on('shown.bs.modal', function () {
          setTimeout(() => { mapEdit.invalidateSize(); }, 300);
      });


      


  });

          $(document).on('click', '#btnPrintQRCode', function() {
            let id = $(this).data('id');

            window.open(
                '/titik-patroli-print-qr/' + id,
                'PrintQR',
                'width=800,height=600,scrollbars=yes,resizable=yes'
            );
        });
</script>
