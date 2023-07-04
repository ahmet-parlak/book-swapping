@extends('template')

@section('title')
    Kitap Düzenle | Panel
@endsection

@section('style')
    <link rel="stylesheet" href="/css/admin/main.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <section class="main">
        <script>
            /* Get Params */
            let params = (new URL(document.location)).searchParams;
            let bookId = params.get('book');
        </script>
        <div class="container">
            <div class="header d-flex flex-row justify-content-between mt-4">
                <div class="pt-2 ps-2 back-btn-flex"><a href="{{ route('panelBooks') }}">
                        <x-icons.books />
                    </a></div>
                <div class="">
                    <h1 class="mt-2 mb-2">Kitap Düzenle</h1>
                </div>
                <div class="pt-2 pe-2 back-btn-flex" data-bs-toggle="tooltip"
                data-bs-placement="right" title="Kitabı Sil"><a href="{{ route('panelDeleteBook') }}?book={{$book->book_id}}">
                        <x-icons.delete />
                    </a></div>
            </div>
            <hr style="margin-top: 0%">
            {{-- States --}}
            @if (request()->get('pageState') == 'Book Exist')
                <script>
                    swal("Dikkat", "Bu ISBN numarasına sahip bir kitap sistemde mevcut.", "warning").then(() => {
                        location.href = window.location.href.split('?')[0] + "?book=" + bookId;
                    });
                </script>
            @endif
            @if (request()->get('pageState') == 'Insert Error')
                <script>
                    swal("Kitap Eklenirken Bir Hata Oluştu", "", "error").then(() => {
                        location.href = window.location.href.split('?')[0] + "?book=" + bookId;
                    });
                </script>
            @endif
            @if (request()->get('pageState') == 'Book Updated')
                <script>
                    swal("Güncelleme Başarılı", "", "success").then(() => {
                        location.href = window.location.href.split('?')[0] + "?book=" + bookId;
                    });
                </script>
            @endif
            @if (request()->get('pageState') == 'Update Error')
                <script>
                    swal("Hata", "Güncelleme Sırasında Bir Hata Meydana Geldi", "error").then(() => {
                        location.href = window.location.href.split('?')[0] + "?book=" + bookId;
                    });
                </script>
            @endif
            @if (Session::get('pageState') == 'Book Not Exist')
                <script>
                    swal("Hata", "Bu kitap sistemde kayıtlı değil.", "error").then(() => {
                        location.href = window.location.href.split('?')[0] + "?book=" + bookId;
                    });
                </script>
            @endif




            {{-- Validation errors --}}

            @if ($errors->any())
                <ul style=" list-style-type:none; color:#cc0621; margin:0% 200px;" class="border border-2 py-2 mb-3 fw-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form id="form" action="{{ route('panelUpdateBook.action') }}" method="POST" enctype="multipart/form-data"
                class="addBook-form col-8">
                @csrf
                <input id="book_id" hidden type="text" name="book_id" value="{{ $book->book_id }}">
                <div class="mb-2">
                    <div class="book-image">
                        <img id="bookImg" src="../{{ $book->image }}" alt="" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="Resim Değiştir">
                    </div>
                    <label id="imageInputLabel" for="" class="form-label"></label>
                    <input id="imageInput" hidden type="file" accept="image/png, image/jpeg" name="image" autocomplete="off"
                        class="addBook form-control text-center">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    {{-- <label for="" class="form-label"></label> --}}
                    <input id="isbnInput" type="number" name="isbn" min="1000000000000" max="9999999999999"
                        autocomplete="off" class="addBook form-control text-center" required disabled
                        value="{{ $book->isbn }}" oninvalid="this.setCustomValidity('ISBN Numarası 13 Haneden Oluşmalı!')"
                        oninput="this.setCustomValidity('')" >
                    <div id="editIsbnLabel" class="form-text" style="cursor: pointer;">ISBN Numarasını Düzenle
                    </div>
                </div>
                <div class="mb-4">
                    {{-- <label for="" class="form-label"></label> --}}
                    <input type="text" name="bookName" placeholder="Kitap Adı" autocomplete="off" class="addBook form-control text-center" required
                        value="{{ $book->book_name }}">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <input type="text" name="author" autocomplete="off" placeholder="Yazar" class="addBook form-control text-center" required
                        value="{{ $book->author }}">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <input type="text" name="publisher" autocomplete="off" placeholder="Yayınevi" class="addBook form-control text-center" required
                        value="{{ $book->publisher }}">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <input type="number" autocomplete="off" placeholder="Yayın Yılı" min="1000" max="3000" name="publishYear"
                        class="addBook form-control text-center" required value="{{ $book->publication_year }}" oninvalid="this.setCustomValidity('Yıl bilgisi 4 basamaklı girilmeli!')"
                        oninput="this.setCustomValidity('')">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <select id="selectBookState" class="form-select" name="state">
                        <option value="active" @if ($book->state == 'active') selected @endif>Aktif</option>
                        <option value="passive" @if ($book->state == 'passive') selected @endif>Pasif</option>
                        <option value="waiting" @if ($book->state == 'waiting') selected @endif>Onay Bekliyor</option>
                    </select>
                    <div class="form-text"></div>
                </div>

                <button id="submitBtn" type="submit" class="btn btn-primary px-4 fs-5">Güncelle</button>
            </form>

        </div>

    </section>

    {{-- <style>
        .footer {
            margin-top: 100px;
            position: relative;
        }

    </style> --}}
@endsection

@section('script')
    <script src="../js/admin/updateBook.js"></script>
@endsection
