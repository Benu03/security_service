<div class="row">
    <div class="col-12">
        <div class="card" style="margin-top: 35px; margin-left: 5px">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="card-title"><b>{{ $title }}</b></h2>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center p-3 rounded shadow-sm" 
                        style="background: linear-gradient(135deg, #ff7e5f, #feb47b); color: white; max-width: 250px;">
                        <i class="fas fa-database fa-2x me-3"></i>
                        <div>
                            <div style="font-size: 14px; margin-left: 10px;">Total Data</div>
                            <div style="font-size: 20px; font-weight: bold; margin-left: 10px;">
                                {{ $count_cust }}
                            </div>
                        </div>
                    </div>
                </div>
             
                     <div class="table-responsive">
                        <table id="dataTableUserAccess" class="display table table-bordered text-center" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr style="background-color: rgba(210, 58, 7, 0.834); color: white;">
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>NIK</th>
                                    <th>Full Name</th>
                                    <th>Customer</th>
                                    <th>Role</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>


            </div>
        </div>
    </div>
</div>

@include('master/modal/users_access_edit')


<script>
    $(document).ready(function () {
        function initUserAccessTable() {
            $('#dataTableUserAccess').DataTable({
                pageLength: 10,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('get-users-access') }}",
                    type: "GET"
                },
                columns: [
                    { 
                        data: null,
                        orderable: false,  
                        searchable: false,  
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'username' },
                    { data: 'nik' },
                    { data: 'fullname' },
                    { data: 'customer' },
                     { data: 'role' },
                    { name: 'created_date', data: 'created_date',
                                render: function(data, type, row) {
                                    if (data) {
                                        let date = new Date(data);
                                        
                                        const months = [
                                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                                        ];

                                        let day = date.getDate(); // Tidak perlu padStart karena formatnya tanpa leading zero
                                        let month = months[date.getMonth()]; // Ambil nama bulan dari array
                                        let year = date.getFullYear();
                                        let hours = String(date.getHours()).padStart(2, '0'); // format 2 digit
                                        let minutes = String(date.getMinutes()).padStart(2, '0'); // format 2 digit

                                        return `${day} ${month} ${year} ${hours}:${minutes}`;
                                    }
                                    return '-';
                            }
                        },
                        { 
                            data: 'id', 
                            orderable: false, 
                            searchable: false,
                            render: function(data) {
                                return `
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                   
                                `;
                            }
                        }
                ]
            });
        }

        initUserAccessTable();


        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $('#modalEditUserAccess').modal('show');
        });

    });
</script>
