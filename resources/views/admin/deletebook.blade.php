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
            <div class="header mt-4">
                <div class="back-btn"><a href="{{ route('panelBooks') }}">
                        <x-icons.books />
                    </a></div>
                <h1 class="mt-2 mb-2">Kitabı Sil</h1>
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

            <form id="form" action="{{ route('panelDeleteBook.action') }}" method="POST" class="addBook-form col-8">
                @csrf
                <div class="mb-2">
                    <div class="book-image">
                        <img id="bookImg" src="../{{ $book->image }}" alt="" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="Resim Değiştir">
                    </div>
                </div>

                <div class="my-3 border-bottom">
                    <h1>{{ $book->book_name }}</h1>
                </div>

                <div class="row mb-2 fs-6 text-start offset-5 ">
                    <div class="col-12 mb-2 ps-0">
                        <span class="fw-bold">Yazar:</span> {{ $book->author }}
                    </div>
                    <div class="col-12 mb-2 ps-0">
                        <span class="fw-bold ">Yayınevi:</span> {{ $book->publisher }}
                    </div>
                    <div class="col-12 mb-2 ps-0">
                        <span class="fw-bold">Yayın Yılı: </span>{{ $book->publication_year }}
                    </div>
                    <div class="col-12 mb-2 ps-0">
                        <span class="fw-bold">ISBN: </span>{{ $book->isbn }}
                    </div>
                </div>


                {{-- <div class="mb-4">
                    <input disabled autocomplete="off" class="addBook form-control text-center"
                        value="ISBN: ">
                </div> --}}
                <input hidden type="text" name="book_id" value="{{ $book->book_id }}">
                <button type="submit" class="btn btn-primary px-5 py-2 mt-4 fs-5">Sil</button>
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
        document.getElementById("form").addEventListener("submit", function(event) {
            event.preventDefault();
            swal({
                    title: "Emin Misiniz?",
                    text: "Kitap Silinecek",
                    icon: "warning",
                    buttons: ['İptal', 'Sil'],
                    dangerMode: true,
                })
                .then((willdelete) => {
                    if (willdelete) {
                        document.getElementById("form").submit();
                    }
                });
        });
    </script>
@endsection
