@extends('template')
@section('title')
    Giriş Yap | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/signin.css">
@endsection


@section('navbar')
    @include('navbar/custom')
@endsection

@section('body')


    @if (request()->get('pageState') == 'Registration Successful')
        <script>
            swal("Kayıt Başarılı", "E-posta adresi ve şifreniz ile giriş yapabilirsiniz.", "success").then(() => {
                location.href = "login";
            });
        </script>
    @endif

    @if (request()->input('password_changed') == 'true')
        <script>
            swal("Parola Güncellendi", "Yeni şifreniz ile giriş yapabilirsiniz.", "success").then(() => {
                location.href = "login";
            });
        </script>
    @endif

    <section class="main mt-4">
        <div class="form-body">
            <div class="row me-0">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">
                            <div class="form-header text-center border-bottom mb-4">
                                <h1>GİRİŞ YAP</h1>
                            </div>
                            {{-- Validation errors --}}

                            @if ($errors->any())
                                <ul style="padding-left:5%">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <form action="{{ route('login.action') }}" method="POST" class="requires-validation" novalidate>
                                @csrf
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" placeholder="E-posta Adresi"
                                        required>
                                    <div class="valid-feedback">Email field is valid!</div>
                                    <div class="invalid-feedback">Email field cannot be blank!</div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <input class="form-control" type="password" name="password" placeholder="Şifre"
                                        required>
                                    <div class="valid-feedback">Password field is valid!</div>
                                    <div class="invalid-feedback">Password field cannot be blank!</div>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <a href="#" class="password-forgot">Şifreni mi unuttun?</a>
                                </div>

                                <div class="form-button mt-4 text-center">
                                    <button id="submit" type="submit" class="btn btn-primary">GİRİŞ YAP</button>
                                </div>
                                <div class="register-link">
                                    <a href="{{ route('signin') }}">Henüz hesabnız yoksa hemen kayıt olun!</a>
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
