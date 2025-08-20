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
                       {{-- Statistik Count --}}
                        <div class="row mb-2">
                            @foreach ($count as $g)
                                <div class="col-md-3 col-sm-6 col-12 mb-3">
                                    <div class="d-flex align-items-center p-3 rounded shadow-sm"
                                        style="background: linear-gradient(135deg, 
                                                {{ $g->category == 'CHECK POINT' ? '#ff7e5f, #ff7e5f' : '#ff7e5f, #ff7e5f' }};
                                                color: white; min-height: 80px;">
                                        <i class="fas {{ $g->category == 'CHECK POINT' ? 'fa-map-marker-alt' : 'fa-user-check' }} fa-2x me-3"></i>
                                        <div>
                                            <div style="font-size: 14px; margin-left: 10px;">{{ $g->category }}</div>
                                            <div style="font-size: 20px; font-weight: bold; margin-left: 10px;">
                                                {{ $g->total }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{-- Tombol Add Vendor --}}
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" 
                                    class="btn btn-sm shadow-sm" 
                                    data-toggle="modal" 
                                    data-target="#modalAddTitikPatroli" 
                                    style="background-color: rgba(226, 106, 8, 0.9); color: white; border-radius: 20px;">
                                <i class="fas fa-plus-circle"></i> Add Titik Patroli
                            </button>
                        </div>
             
                     <div class="table-responsive">
                        <table id="dataTabletitikPatroli" class="display table table-bordered text-center" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr style="background-color: rgba(210, 58, 7, 0.834); color: white;">
                                    <th>No</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Position</th>
                                    <th>Radius (m)</th>
                                    <th>Location</th>
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



@include('master/modal/titik_patroli_add')
@include('master/modal/titik_patroli_edit')
@include('master/modal/position_show')



<script>
    $(document).ready(function () {
        function initTitikPatroliTable() {
            $('#dataTabletitikPatroli').DataTable({
                pageLength: 10,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('get-titik-patroli') }}",
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
                    { data: 'customer_name' },
                    { data: 'category' },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            if (row.latitude && row.longitude) {
                                return `
                                    <button class="btn btn-success btn-sm btn-map" 
                                            data-lat="${row.latitude}" 
                                            data-lng="${row.longitude}" 
                                            data-location="${row.location}"
                                             data-radius="${row.radius}">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                `;
                            }
                            return '-';
                        }
                    },
                    { data: 'radius' },
                    { data: 'location' },
                    { name: 'created_date', data: 'created_date',
                                render: function(data, type, row) {
                                    if (data) {
                                        let date = new Date(data);
                                        
                                        const months = [
                                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                                        ];

                                        let day = date.getDate();
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
                            render: function(data, type, row) {
                                let btn = `
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                `;

                                if (row.category === 'CHECK POINT') {
                                    btn += `
                                        <button class="btn btn-secondary btn-sm btn-print-qr ms-1" data-id="${data}">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                    `;
                                }

                                return btn;
                            }
                        }
                ]
            });
        }

        initTitikPatroliTable();


        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $('#modalEditTitikPatroli').modal('show');
        });


        $(document).on('click', '.btn-print-qr', function() {
            let id = $(this).data('id');

            window.open(
                '/titik-patroli-print-qr/' + id,
                'PrintQR',
                'width=800,height=600,scrollbars=yes,resizable=yes'
            );
        });




    });
</script>
