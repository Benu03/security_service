<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-modal">
            <div class="modal-body p-0">
                <div class="row justify-content-center align-items-center mx-0 no-gutters">
                    <div class="col-lg-12 px-0">
                        <div class="card border-0 shadow-lg mb-0">
                            <div class="row no-gutters">
                                <div class="col-md-4 text-center text-white p-4 gradient-custom"
                                    style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                    <img src="{{ asset('img/user.png') }}"
                                        alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 80px;">
                                    <h5 class="mb-0 text-uppercase">{{ Session::get('user')['full_name'] }}</h5>
                                    <p class="mb-4">{{ Session::get('user')['username'] }}</p>
                                  
                                    <p class="text-muted mb-0">
                                        <i class="fab fa-whatsapp text-white"></i>
                                        <a href="https://web.whatsapp.com/send?phone={{ Session::get('user')['wa_number'] }}" target="_blank" class="text-white">
                                            {{ Session::get('user')['wa_number'] }}
                                        </a>
                                    </p>

                                    <p class="text-white mt-2 mb-0 fw-bold">
                                        {{ Session::get('modules')['role'] }}
                                    </p>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h6 class="mb-3">Information</h6>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <h6>Email</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['email'] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <h6>NIK</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['nik'] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <h6>Entity</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['entity'] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <h6>Division</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['division'] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <h6>Department</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['department'] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <h6>Position</h6>
                                                <p class="text-muted mb-0">{{ Session::get('user')['position'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!-- card -->
                    </div>
                </div> <!-- row -->
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-modal {
        border-radius: 20px;
    }

    .gradient-custom {
        background: linear-gradient(90deg, #ce4444, #d3873b);
    }
</style>
