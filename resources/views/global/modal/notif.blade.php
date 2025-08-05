<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content rounded-modal">
        <div class="modal-header" style="position: relative; padding: 1rem;">
          <div class="container">
              <div class="row justify-content-center">
                  <div class="col-auto">
                      <img src="{{ config('static.url_portal_ts3_main') }}img/logo/logo.png" 
                           alt="Notification Image" 
                           style="width: 90px; height: auto;">
                  </div>
              </div>
          </div>
          <button type="button" class="close" style="position: absolute; top: 15px; right: 15px;" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
        <div class="modal-body" id="notification-modal-body">
          <!-- Notifikasi detail akan ditampilkan di sini -->
        </div>

      </div>
    </div>
  </div>
  <style>
    .rounded-modal {
        border-radius: 20px;
    }
  </style>