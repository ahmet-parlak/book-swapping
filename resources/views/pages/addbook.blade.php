@extends('template')
@section('title')
    Kitap Ekle | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/store.css">
@endsection


@section('navbar')
    @include('navbar/navbar')
@endsection




@section('body')

    <script>
        let addFavoritesUrl = '{{ route('addFavorites') }}',
            addBookshelfUrl = '{{ route('addBookshelf') }}',
            storeUrl = '{{ route('store') }}'
        /* token = '{{ csrf_token() }}'; */
    </script>

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

            <div class="header mt-4 mb-4 col-10 offset-1">
                <h1 class="mt-2 mb-2">Kitap Ekle</h1>
                <hr>
                <p class="col-8 offset-2 alert alert-secondary fw-bold">Lütfen Kitap Bilgilerini Girin</p>
            </div>

            <form action="{{ route('addbook') }}" method="POST" enctype="multipart/form-data" class="addBook-form col-8">
                @csrf
                <div class="mb-4">
                    <input type="text" name="bookName" autocomplete="off" class="addBook form-control text-center"
                        placeholder="Kitap" aria-describedby="emailHelp" required>
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <input type="text" name="author" autocomplete="off" class="addBook form-control text-center"
                        placeholder="Yazar" aria-describedby="emailHelp">
                    <div class="form-text"></div>
                </div>
                <div class="mb-4">
                    <input id="isbnInput" type="number" name="isbn" min="1000000000000" max="9999999999999"
                        autocomplete="off" placeholder="ISBN" class="addBook form-control text-center"
                        aria-describedby="emailHelp" required
                        oninvalid="this.setCustomValidity('ISBN Numarası 13 Haneden Oluşmalı!')"
                        oninput="this.setCustomValidity('')">
                    <span id="questionBtn" class="question-mark">
                        <x-icons.question-mark />
                    </span>
                    <div id="questionAnswer" class="d-none my-3"><img src="media/icons/isbn-location.png" alt=""></div>
                    <div class="form-text">13 Haneli Uluslararası Standart Kitap Numarası</div>
                </div>


                {{-- Validation errors --}}
                @if ($errors->any())
                    <ul style="padding-left:5%">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <button type="submit" class="btn btn-primary px-4 fs-5">Ekle</button>
            </form>

        </div>

    </section>

   {{--  <style>
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
        }

    </style> --}}
    <script>
        function showAlert(header, message, type) {
            swal(header, message, type).then(function() {
                location.href = "{{ route('store') }}"
            });
        }
    </script>
    @if (Request::get('state'))
        @switch(Request::get("state"))
            @case('addBooksuccess')
                <script>
                    showAlert("Talebiniz Alındı", "", "success");
                </script>
            @break

            @case('addBookerror')
                <script>
                    showAlert("Hata Meydana Geldi", "", "error");
                </script>
            @break
        @endswitch
    @endif

@endsection

@section('script')
    <script>
        const questionBtn = document.getElementById("questionBtn");
        const answerDiv = document.getElementById("questionAnswer");

        questionBtn.addEventListener("click", function() {
            answerDiv.classList.toggle("d-none");
        })
    </script>
@endsection
