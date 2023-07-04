@extends('template')
@section('title')
    Kayıt Ol | Liboo
@endsection
@section('style')
    <link rel="stylesheet" href="css/register.css">
@endsection

@section('navbar')
    @include('navbar/custom')
@endsection


@section('body')
    <section class="main mt-4">
        <div class="form-body">
            <div class="row me-0">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">
                            <div class="form-header text-center border-bottom mb-4">
                                <h1>KAYIT OL</h1>
                            </div>
                            {{-- Validation errors --}}
                                @if ($errors->any())
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            

                            <form id="register-form" action="{{route('signin')}}" method="POST" class="requires-validation" novalidate>
                                @csrf
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" placeholder="E-posta Adresi"
                                        required autocomplete="off">
                                    <div id="mail-feedback" class="invalid-feedback ps-3">Lütfen geçerli bir mail adresi
                                        girin!</div>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="firstName" placeholder="Ad"
                                                required autocomplete="off">

                                        </div>
                                        <div class="col-md-6 ps-0">
                                            <input class="form-control" type="text" name="lastName" placeholder="Soyadı"
                                                required autocomplete="off">

                                        </div>
                                    </div>
                                    <div id="name-feedback" class="invalid-feedback ps-3">Lütfen ad soyad bilginizi girin!
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select id="select-city" name="city" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example">
                                                <option selected disabledphp>İl</option>
                                            </select>
                                            <div id="region-feedback" class="invalid-feedback ps-3 d-absolute">Lütfen il ve
                                                ilçe seçin!</div>
                                        </div>

                                        <div class="col-md-6 ps-0">
                                            <select id="select-district" name="district" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example" disabled>
                                                <option selected>İlçe</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control" type="password" name="password"
                                                placeholder="Şifre" required>

                                        </div>
                                        <div class="col-md-6 ps-0">
                                            <input class="form-control" type="password" name="password2"
                                                placeholder="Şifre (Tekrar)" required>
                                        </div>

                                    </div>
                                    <div id="password-feedback" class="invalid-feedback ps-3">Lütfen en az 6 karakter
                                        uzunluğunda bir
                                        parola belirleyin!</div>
                                </div>

                                <div class="form-button mt-4 text-center">
                                    <button id="submit" type="submit" class="btn btn-primary">KAYIT OL</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img id="banner" src="brand/banner.png" alt="banner" class="mb-4">
    </section>
@endsection

@section('script')
    <script src="js/register.js"></script>
@endsection
