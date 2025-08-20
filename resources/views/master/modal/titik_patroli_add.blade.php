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


<div class="modal fade" id="modalAddTitikPatroli" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 90%; width: auto;">
    <form id="formAddTitikPatroli">
      @csrf
      <div class="modal-content">
        
        <!-- HEADER -->
        <div class="modal-header" style="background-color: rgba(226, 106, 8, 0.85); color: white;">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Titik Patroli</h5>
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
              <select class="form-control select2" id="customer" name="customer" required>
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $customer)
                  <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Kategori -->
            <div class="form-group col-md-6">
              <label><i class="fas fa-tags"></i> Kategori</label>
              <div class="d-flex">
                <!-- Check Point -->
                <div class="custom-radio-card mr-3 flex-fill">
                  <input type="radio" id="catCheckpoint" name="category" value="CHECK POINT" required>
                  <label for="catCheckpoint">
                    <i class="fas fa-map-marker-alt"></i><br>
                    CHECK POINT
                  </label>
                </div>
                <!-- Presensi -->
                <div class="custom-radio-card flex-fill">
                  <input type="radio" id="catPresensi" name="category" value="PRESENSI">
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
              <textarea class="form-control" id="location" name="location" placeholder="Nama lokasi / deskripsi" rows="3" required></textarea>
              
              <label for="radius" class="mt-2"><i class="fas fa-ruler"></i> Radius (m)</label>
              <input type="number" class="form-control" id="radius" name="radius" placeholder="Masukkan radius (meter)">
              
              <label for="latitude" class="mt-2"><i class="fas fa-globe"></i> Latitude</label>
              <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
              
              <label for="longitude" class="mt-2"><i class="fas fa-globe"></i> Longitude</label>
              <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
            </div>

            <!-- Peta -->
            <div class="form-group col-md-6">
              <label><i class="fas fa-map-marked-alt"></i> Pilih Titik pada Peta</label>
              <div id="mapAdd" style="height:400px; width:100%;"></div>

            </div>
          </div>
        </div>

        <div class="modal-footer">
          {{-- <input type="hidden" id="editId" name="id"> --}}
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
    $('#formAddTitikPatroli').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Menyimpan data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ route('add-titik-patroli') }}",
            type: "POST",
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                Swal.close();
                if (res.success) {
                    $('#modalAddTitikPatroli').modal('hide');
                    $('#formAddTitikPatroli')[0].reset(); 
                    $('#dataTabletitikPatroli').DataTable().ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Data berhasil ditambahkan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server'
                });
            }
        });
    });

</script>



<script>
    $(document).ready(function() {
    let mapAdd, markerAdd, circleAdd;

    // Inisialisasi map saat dokumen siap
    mapAdd = L.map('mapAdd').setView([-6.200000, 106.816666], 13); // Jakarta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(mapAdd);

    // Klik map untuk pilih titik
    mapAdd.on('click', function(e) {
        const { lat, lng } = e.latlng;

        if (markerAdd) markerAdd.setLatLng([lat, lng]);
        else markerAdd = L.marker([lat, lng]).addTo(mapAdd);

        $('#latitude').val(lat.toFixed(6));
        $('#longitude').val(lng.toFixed(6));

        let radius = $('#radius').val();
        if (circleAdd) mapAdd.removeLayer(circleAdd);
        if (radius) {
        circleAdd = L.circle([lat, lng], {
            radius: radius,
            color: 'blue',
            fillColor: '#3f8efc',
            fillOpacity: 0.3
        }).addTo(mapAdd);
        }
    });

    // Update circle saat radius berubah
    $('#radius').on('input', function() {
        let lat = $('#latitude').val();
        let lng = $('#longitude').val();
        let radius = $(this).val();

        if (lat && lng) {
        if (circleAdd) mapAdd.removeLayer(circleAdd);
        circleAdd = L.circle([lat, lng], {
            radius: radius,
            color: 'blue',
            fillColor: '#3f8efc',
            fillOpacity: 0.3
        }).addTo(mapAdd);
        }
    });

    // Reset form saat modal ditutup
    $('#modalAddTitikPatroli').on('hidden.bs.modal', function() {
        $('#formAddTitikPatroli')[0].reset();
        $('#latitude').val('');
        $('#longitude').val('');
        if (markerAdd) { mapAdd.removeLayer(markerAdd); markerAdd = null; }
        if (circleAdd) { mapAdd.removeLayer(circleAdd); circleAdd = null; }
    });

    // Fix render map saat modal muncul
    $('#modalAddTitikPatroli').on('shown.bs.modal', function() {
        setTimeout(() => { mapAdd.invalidateSize(); }, 300);
    });
    });
</script>