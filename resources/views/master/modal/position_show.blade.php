<div class="modal fade" id="modalMapPosition" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#d23a07; color:white;">
        <h5 class="modal-title"><i class="fas fa-map-marker-alt"></i> Location Map</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="map" style="height: 400px; width: 100%;"></div>
      </div>
    </div>
  </div>
</div>


<script>
    let map; 
    let marker;
    let circle;

$(document).on('click', '.btn-map', function() {
    let lat = $(this).data('lat');
    let lng = $(this).data('lng');
    let location = $(this).data('location');
    let radius = $(this).data('radius');

    $('#modalMapPosition').modal('show'); // ✅ sesuai ID modal

    setTimeout(function() {
        if (!map) {
            map = L.map('map').setView([lat, lng], 19);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        } else {
            map.setView([lat, lng], 19);
        }

        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);

        marker = L.marker([lat, lng]).addTo(map)
                 .bindPopup(`${location}<br>Radius: ${radius} m`).openPopup();

        circle = L.circle([lat, lng], {
            radius: radius,
            color: 'blue',
            fillColor: '#3f8efc',
            fillOpacity: 0.3
        }).addTo(map);

        

    }, 500);
});

</script>