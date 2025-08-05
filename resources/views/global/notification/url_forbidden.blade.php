@include('layout/head')
@include('layout/header')
@include('layout/menu')


<div class="row" style="height: 100vh;">
    <div class="col-md-12 d-flex justify-content-center align-items-center">
        <img class="img-fluid" 
             src="{{ asset('img/forbidden.png') }}" 
             alt="Forbidden Image" 
             style="width: 270px; border-radius: 50px;">
    </div>
</div>


@include('layout/footer')