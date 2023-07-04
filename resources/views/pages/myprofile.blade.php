@extends('template')
@section('title')
    User | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/myprofile.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container mt-5">

        @if (request()->input('extensionError') == 'true')
            <script>
                swal("Hatalı Dosya Uzantısı!", "Lütfen bir resim dosyası seçin.", "error");
            </script>
        @endif
        @if (request()->input('oversize') == 'true')
            <script>
                swal("Yüksek Dosya Boyutu!", "Yüklemek istediğiniz resmin boyutu çok yüksek.", "error");
            </script>
        @endif

        @if (request()->input('ppUpdate') == 'error')
            <script>
                swal("Hata", "Güncelleme sırasında bir hata meydana geldi.", "error");
            </script>
        @endif

        @if (request()->input('ppUpdate') == 'success')
            <script>
                swal("", "Profil Fotoğrafı Güncellendi", "success");
                location.href = '{{ route('myprofile') }}';
            </script>
        @endif


        <div class="row mt-1 align-items-center">
            <div class="col-1 offset-3 text-end">
                <div class="user-pp" id="edit-pp" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Profil Fotoğrafını Değiştir">
                    <img src="{{ Auth::user()->user_photo }}" alt="">
                </div>
                <form id="pp-form" action="{{ route('upload-pp') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input id="pp-input" type="file" name="photo" accept="image/png, image/jpeg" hidden>
                </form>
            </div>
            <div class="col-4 offset-1">
                <div class="row user">
                    <div id="username" class="col-9 text-center">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}</div>
                    <div class="col-1 edit-box pe-5" id="edit-name" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="İsim Düzenle">
                        <x-icons.edit-box />
                    </div>

                </div>

                <div class="row align-items-center">
                    <div class="col-md-5">
                        <input id="fName-input" class="form-control m-0" type="text" name="firstName" placeholder="Ad"
                            required autocomplete="off" hidden>
                    </div>
                    <div class="col-md-5 ps-0">
                        <input id="lName-input" class="form-control m-0" type="text" name="lastName" placeholder="Soyad"
                            required autocomplete="off" hidden>
                    </div>
                    <div class="col-1 edit-box pe-5" id="confirm-name" hidden data-bs-toggle="tooltip"
                        data-bs-placement="right" title="Güncelle">
                        <x-icons.check-box />
                    </div>
                </div>
                <div class="warning pt-2" id="name-warning">

                </div>

            </div>

        </div>
        <div class="row offset-2">
            <div class="col-10">
                <hr>
            </div>
        </div>
        <div class="col-md-6 mb-2 offset-3">
            <div class="row  align-items-center border-bottom">
                <div class="col-8 fw-bold  pt-3">
                    <h3>Hesap Bilgileri</h3>
                </div>
            </div>
            <div class="row  align-items-center mt-3">
                <div class="col-4 fw-bold fs-5 text-end">
                    E-posta:
                </div>
                <div class="col-6 fs-5">
                    <input class="form-control" type="text" value="{{ Auth::user()->email }}" disabled>
                </div>
                <div class="col-2 edit-box">

                </div>
            </div>
            <div class="row  align-items-center my-4">
                <div class="col-4 fw-bold fs-5 text-end">
                    Şifre:
                </div>
                <div class="col-6 fs-5">
                    <div>
                        <input class="form-control" type="password" name="password" id="password-input"
                            value="*******************" placeholder="Yeni Parola" disabled>
                    </div>
                    <div class="mt-2">
                        <input class="form-control" type="password" name="confirm" id="confirm-input"
                            placeholder="Parola Onay" hidden>
                    </div>
                    <div class="warning" id="password-warning">

                    </div>
                </div>
                <div class="col-1 edit-box" id="edit-password" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Parolayı Değiştir">
                    <x-icons.edit-box />
                </div>
                <div class="col-1 edit-box" id="confirm-password" hidden data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Güncelle">
                    <x-icons.check-box />
                </div>
            </div>
            <br>
            <div class="row align-items-center mt-4 border-bottom">
                <div class="col-8 fw-bold fs-5">
                    <h3>İletişim Bilgileri</h3>
                </div>
            </div>

            <div class="row  align-items-center mt-3">
                <div class="col-2 fs-5 text-end">
                    <span class="fw-bold">İl/İlçe:</span>
                </div>
                <div class="col-4 fs-5">
                    {{-- <span class="fw-bold">İlçe:</span> --}}
                    <select class="form-control" name="" id="select-city" disabled>
                        <option value="">{{ Auth::user()->city }}</option>
                    </select>
                </div>
                <div class="col-4 fs-5">
                    <select class="form-control" name="" id="select-district" disabled>
                        <option value="">{{ Auth::user()->district }}</option>
                    </select>
                </div>
                <div class="col-1 edit-box" id="edit-region" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Düzenle">
                    <x-icons.edit-box />
                </div>
                <div class="col-1 edit-box" id="confirm-region" hidden data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Güncelle">
                    <x-icons.check-box />
                </div>
            </div>

            <div class="row  align-items-center mt-4">
                <div class="col-2 fw-bold fs-5 text-end">
                    Telefon:
                </div>
                <div class="col-8 fs-5 text-center">
                    @if (Auth::user()->phone_number)
                        <p class="fs-5 align-middle p-0 m-0" id="phone-span">
                            @php
                                $numbers = str_split(Auth::user()->phone_number);
                                for ($i = 0, $j = 0; $i <= strlen(Auth::user()->phone_number) + 2; $i++, $j++) {
                                    if ($i == 3 || $i == 7 || $i == 10) {
                                        echo ' ';
                                        $j--;
                                    } else {
                                        echo $numbers[$j];
                                    }
                                }
                            @endphp</p>
                        <input class="form-control tel text-center" type="tel" id="phone-input" placeholder="000 000 00 00"
                            maxlength="13" hidden>
                        <div class="warning" id="phone-warning"></div>
                    @else
                        <span class="fs-6" id="phone-span">Telefon Numaranızı Ekleyin</span>
                        <input class="form-control tel text-center" type="tel" id="phone-input" placeholder="000 000 00 00"
                            maxlength="13" hidden>
                        <div class="warning" id="phone-warning"></div>
                    @endif
                </div>
                <div class="col-1 edit-box" id="edit-phone" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Düzenle">
                    <x-icons.edit-box />
                </div>
                <div class="col-1 edit-box" id="confirm-phone" hidden data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Güncelle">
                    <x-icons.check-box />
                </div>

            </div>

            <script>
                let ajax_url = '{{ route('myprofile') }}',
                let logout_url = '{{ route('logout') }}';
            </script>

        </div>
    </div>
@endsection



@section('script')
    <script src="js/myprofile.js"></script>
@endsection
