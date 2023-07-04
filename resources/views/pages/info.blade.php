@extends('template')
@section('title')
    Bilgilendirme | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/info.css">
@endsection


@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container ">
        <div class="header text-center">
            <img id="banner" src="brand/name.png" alt="banner">
            <h1 class="mt-2 mb-0">Kitap Takas Platformu</h1>
        </div>
        <hr>
        <div class="content">
            <div class="row align-items-center">
                <div class="col-sm-12 col-md-2 text-center bookstore-icon">
                    <x-icons.bookstore />
                </div>
                <div class="col-sm-12 col-md-10">
                    <h4>
                        Store'de takas yapabileceğiniz kitaplar listelenir.
                    </h4>
                </div>
            </div>
            <div class="row mt-5 align-items-center">
                <div class="col-sm-12 col-md-2 bookshelf-icon text-center">
                    <x-icons.bookshelf />
                </div>
                <div class="col-sm-12 col-md-10">
                    <h4>
                        Kitaplığınızda takasta kullanacağınız kitaplarınız listelenir.
                    </h4>
                </div>
            </div>
            <div class="row mt-5 align-items-center">
                <div class="col-sm-12 col-md-2 swap-icon text-center">
                    <x-icons.swap />
                </div>
                <div class="col-sm-12 col-md-10">
                    <h4>
                        Takaslar sayfasında aktif ve geçmiş takaslarınız listelenir.
                    </h4>
                </div>
            </div>
            <div class="row mt-5 align-items-center">
                <div class="col-sm-12 col-md-2 swap-icon text-center">
                    <div class="nav-user-pp text-center">
                        <a href="{{ route('myprofile') }}"><img src="../{{ Auth::user()->user_photo }}" alt="" style="width:100px; height:100px" ></a>
                    </div>

                </div>
                <div class="col-sm-12 col-md-10">
                    <h4>
                        Profil simgesine tıklayıp hesap ayarlarınızı  yapabilirsiniz.
                    </h4>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <div class="btn btn-primary fs-4"><a href="{{ route('home') }}" style="color:white">TAMAM</a></div>
        </div>
    </div>
    <div class="space">

    </div>
@endsection
