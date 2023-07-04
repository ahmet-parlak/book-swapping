@extends('template')

@section('title')
    Kitap Ekle | Panel
@endsection

@section('style')
    <link rel="stylesheet" href="/css/admin/main.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <section class="main">

        <div class="container">

            {{-- States --}}
            @if (request()->get('pageState') == 'Book Added')
                <script>
                    swal({
                            title: "Kitap Eklendi",
                            text: "Ne yapmak istersiniz?",
                            icon: "success",
                            buttons: ['Kitap Ekle', 'Kitaplar\'a Dön'],
                            dangerMode: false,
                        })
                        .then((willdelete) => {
                            if (willdelete) {
                                location.href = "{!! route('panelBooks') !!}";
                            }
                        });
                </script>
            @endif
            @if (request()->get('pageState') == 'Insert Error')
                <script>
                    swal("Kitap Eklenirken Bir Hata Oluştu", "", "error").then(() => {

                    });
                </script>
            @endif
            @if (request()->get('pageState') == 'Book Updated')
                <script>
                    swal("Güncelleme Başarılı", "", "success").then(() => {
                        location.href = "{!! route('panelBooks') !!}";
                    });
                </script>
            @endif
            @if (request()->get('pageState') == 'Update Error')
                <script>
                    swal("Hata", "Güncelleme Sırasında Bir Hata Meydana Geldi", "error").then(() => {

                    });
                </script>
            @endif

            <div class="header mt-4 col-8 offset-2">
                <div class="back-btn"><a href="{{ route('panelBooks') }}">
                        <x-icons.books />
                    </a></div>
                <h1 class="mt-2 mb-2">Kitap Ekle</h1>
                <hr>
            </div>


            {{-- Validation errors --}}

            @if ($errors->any())
                <ul style="padding-left:5%">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('panelAddBook.action') }}" method="POST" enctype="multipart/form-data"
                class="addBook-form col-8">
                @csrf
                <div class="mb-2">
                    <div class="book-image">
                        <img id="bookImg" src="../media/books/default.jpg" alt="" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="Resim Değiştir">
                    </div>
                    <label id="imageInputLabel" for="exampleInputEmail1" class="form-label"></label>
                    <input id="imageInput" hidden type="file" accept="image/png, image/jpeg" name="image" autocomplete="off"
                        class="addBook form-control text-center" aria-describedby="emailHelp">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label"></label> --}}
                    <input type="number" name="isbn" min="1000000000000" max="9999999999999" autocomplete="off"
                        placeholder="ISBN" class="addBook form-control text-center" aria-describedby="emailHelp" required
                        oninvalid="this.setCustomValidity('ISBN Numarası 13 Haneden Oluşmalı!')"
                        oninput="this.setCustomValidity('')">
                    <div class="form-text">13 Haneli Uluslararası Standart Kitap Numarası
                    </div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label">Kitap Adı</label> --}}
                    <input type="text" name="bookName" autocomplete="off" class="addBook form-control text-center"
                        placeholder="Kitap" aria-describedby="emailHelp" required>
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label">Yazar</label> --}}
                    <input type="text" name="author" autocomplete="off" class="addBook form-control text-center"
                        placeholder="Yazar" aria-describedby="emailHelp" required>
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label">Yayınevi</label> --}}
                    <input type="text" name="publisher" autocomplete="off" class="addBook form-control text-center"
                        placeholder="Yayınevi" aria-describedby="emailHelp" required>
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label">Yayın Yılı</label> --}}
                    <input type="number" autocomplete="off" min="1500" max="2099" name="publishYear"
                        placeholder="Yayın Yılı" class="addBook form-control text-center" aria-describedby="emailHelp"
                        required oninvalid="this.setCustomValidity('Yıl bilgisi 4 basamaklı girilmeli!')"
                        oninput="this.setCustomValidity('')">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="exampleInputEmail1" class="form-label">Durum</label> --}}
                    <select class="form-select" name="state" id="">
                        <option value="active">Aktif</option>
                        <option value="passive">Pasif</option>
                        <option value="waiting">Onay Bekliyor</option>
                    </select>
                    <div class="form-text"></div>
                </div>

                <button type="submit" class="btn btn-primary px-4 fs-5">Ekle</button>
            </form>

        </div>

    </section>

    {{-- <style>
        .footer {
            margin-top: 100px;
            position: relative;
        }

    </style> --}}
    <script>
        const bookImg = document.getElementById("bookImg");
        const bookImgInput = document.getElementById("imageInput");
        bookImg.addEventListener("click", function() {
            bookImgInput.click();
        })

        bookImgInput.addEventListener("change", function() {
            const imgName = bookImgInput.value.split("\\");
            document.getElementById("imageInputLabel").textContent = imgName[imgName.length - 1];
        })
    </script>
@endsection
