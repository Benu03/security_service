<body class="hold-transition sidebar-mini layout-fixed pace-progress-inner">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('lobby') }}" class="btn btn-sm rounded-pill" style="background-color: #ce4444; color: white;">
                <i class="fa fa-home"></i> Lobby
              </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link text-info" data-toggle="dropdown" href="#" aria-expanded="false"  id="notification-dropdown">
            <i class="fas fa-bell mr-3"></i>
            <span class="badge badge-warning navbar-badge" id="notification-count"><strong>0</strong></span>
        
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right rounded-4">
            <span class="dropdown-header" id="notification-header">No Notifications</span>
            <div class="dropdown-divider"></div>
            <div id="notification-list" class="notification-scroll"></div>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
        </li>

      

        <!-- Profile Link -->
        <li class="nav-item ml-2">
          <a class="nav-link text-success" href="#" data-toggle="modal" data-target="#profileModal" id="profile">
          <i class="fas fa-user-circle"></i>
          </a>
        </li>

        <!-- Logout Link -->
        <li class="nav-item ml-2">
          <a class="nav-link loglog text-danger"  href="#">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        

        </li>
      </ul>
    </nav>


    @include('global.modal.wrapper',['id_modal'=>'modal-notif','modal_content'=>'modal-notif-content'])
    @include('global.modal.profile')
  
    @include('global.modal.notif')



    <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Menambahkan modal secara dinamis
          var modalHtml = `
              <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5> 
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body" id="notification-modal-body">
                              <!-- Notifikasi detail akan ditampilkan di sini -->
                          </div>
                         
                      </div>
                  </div>
              </div>
          `;
  
          // Menyisipkan modal ke dalam body HTML
          document.body.insertAdjacentHTML('beforeend', modalHtml);
  
          function fetchNotifications() {
              fetch("{{ route('getnotif') }}")
                  .then(response => response.json())
                  .then(notifications => {
                      console.log(notifications); 
                      var notificationList = document.getElementById('notification-list');
  
                      var unreadNotifications = notifications.filter(function(notification) {
                          return notification.is_read === null;
                      });
  
                      var notificationCount = unreadNotifications.length;
  
                      document.getElementById('notification-count').innerText = notificationCount;
                      
  
                      document.getElementById('notification-header').innerHTML = '<strong>' + notificationCount + '</strong> Notifications';
  
                      notificationList.innerHTML = '';
  
                      if (notificationCount > 0) {
                          notifications.forEach(function(notification) {
                              var timeAgo = calculateTimeAgo(notification.created_date);
                              var categoryClass = "";
                              var categoryIcon = "";
                              var readClass = "";
  
                              if (notification.category_name === 'info') {
                                  categoryClass = "text-info";
                                  categoryIcon = "fas fa-info-circle";
                              } else if (notification.category_name === 'general') {
                                  categoryClass = "text-success";
                                  categoryIcon = "fas fa-globe-asia";
                              }
  
                              if (notification.is_read === true) {
                                readClass = "text-secondary";
                              } else {
                                  readClass = "text-dark fw-bold"; 
                              }
                                              
                              var item = `
                                  <a href="#" class="dropdown-item ${readClass}" data-notif-id="${notification.id}">
                                      <i class="${categoryIcon} ${categoryClass} me-2"></i>
                                      <span><strong>${notification.module}</strong></span> 
                                      <span class="float-end text-muted text-sm">${timeAgo}</span><br>
                                      <small>${notification.title}</small>
                                  </a>
                                  <div class="dropdown-divider"></div>
                              `;
                              notificationList.insertAdjacentHTML('beforeend', item);
                          });
  
                          document.querySelectorAll('.dropdown-item').forEach(item => {
                              item.addEventListener('click', function(e) {
                                  e.preventDefault();
  
                                  var notifId = this.getAttribute('data-notif-id');
                                  var clickedNotification = notifications.find(function(n) {
                                      return n.id == notifId;
                                  });
                                        if (clickedNotification) {
                                          document.getElementById('notification-modal-body').innerHTML = `
                                          <div class="card border-light shadow-sm">
                                             <span class="badge text-white" style="background-color: #03830a; font-size: 1.2rem;">${clickedNotification.module}</span>
                                              <div class="card-body">
                                                  <h5 class="card-title d-flex justify-content-between align-items-center">
                                                      <span><strong>${clickedNotification.title}</strong></span>
                                                     
                                                  </h5>
                                                  <hr> <hr>
                                                  <div class="mb-3">
                                                      <h6><i class="fas fa-info-circle"></i> <strong>Message</strong></h6>
                                                      <p class="text-muted">${clickedNotification.detail}</p>
                                                  </div>
                                                  <div class="mb-3">
                                                      <h6><i class="fas fa-file-alt"></i> <strong>Content</strong></h6>
                                                      <p class="text-muted">${clickedNotification.data_content}</p>
                                                  </div>
                                                  <div class="mb-3">
                                                      <h6><i class="fas fa-clock"></i> <strong>Created</strong></h6>
                                                      <p class="text-muted">${new Date(clickedNotification.created_date).toLocaleString()}</p>
                                                  </div>
                                              </div>
                                          </div>
                                      `;
  
  
                                                
                                                  var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
                                                  notificationModal.show();
  
  
                                                  fetch("{{ route('updatenotif') }}", {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: JSON.stringify({ notif_id: notifId })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        console.log('Status notifikasi berhasil diperbarui');
                                                    } else {
                                                        console.error('Gagal memperbarui notifikasi');
                                                    }
                                                })
                                                .catch(error => console.error('Gagal memperbarui notifikasi', error));
                                              } else {
                                                    console.error('Notifikasi tidak ditemukan');
                                                }
                              });
                          });
                      } else {
                          notificationList.insertAdjacentHTML('beforeend', '<a href="#" class="dropdown-item text-center">No notifications</a>');
                      }
                  })
                  .catch(error => {
                      console.error('Gagal memuat notifikasi', error);
                      document.getElementById('notification-list').insertAdjacentHTML('beforeend', '<a href="#" class="dropdown-item text-center">Failed to load notifications</a>');
                  });
          }
  
  
          fetchNotifications();
  
  
          document.getElementById('notification-dropdown').addEventListener('click', function() {
              fetchNotifications();
          });
  
          setInterval(fetchNotifications, 300000); 
  
          function calculateTimeAgo(timestamp) {
              var date = new Date(timestamp.replace(' ', 'T')); 
              var now = new Date();
              var diff = Math.floor((now - date) / 1000); 
  
              if (diff < 60) return diff + " secs ago";
              if (diff < 3600) return Math.floor(diff / 60) + " mins ago";
              if (diff < 86400) return Math.floor(diff / 3600) + " hours ago";
              return Math.floor(diff / 86400) + " days ago";
          }
      });
  </script>